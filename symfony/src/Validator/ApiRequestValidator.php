<?php

namespace App\Validator;


class ApiRequestValidator
{
    private $code;
    private $message;
    private $status = false;
    protected static $codes = array(
        0 => 'Valid',
        100 => 'Validation error',
        101 => 'Param data is required',
        201 => 'Param title is required',
        301 => 'Param author is required',
        401 => 'Param editorial is required',
        203 => 'Param password is required',
        206 => 'Token not found',
        302 => 'Token decrypted not corresponding with the user',
        303 => 'Token expired',
        402 => 'Param email is required',
    );

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function validateCreateBook($params = array())
    {
        if (!is_array($params)){
            $validatorResponse = $this->generateValidatorResponse(100);
        }else if (count($params) == 0){
            $validatorResponse = $this->generateValidatorResponse(101);
        } else if (!isset($params['title'])) {
            $validatorResponse = $this->generateValidatorResponse(201);
        } else if (!isset($params['author'])) {
            $validatorResponse = $this->generateValidatorResponse(301);
        } else if (!isset($params['editorial'])) {
            $validatorResponse = $this->generateValidatorResponse(401);
        } else {
            // Valid exit
            $validatorResponse = $this->generateValidatorResponse(null, $status = true);
        }

        return $validatorResponse;
    }

    /**
     * Generate Validator Response
     *
     * @param null $code
     * @param boolean $status
     * @internal param string $message
     * @return boolean
     */
    private function generateValidatorResponse($code = null, $status = false)
    {
        if ($code === null) {
            $code = 0;
        }

        $this->setStatus($status);
        $this->setCode($code);
        $this->setMessage(self::$codes[$code]);

        return $status;
    }
}

