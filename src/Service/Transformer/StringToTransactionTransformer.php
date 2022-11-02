<?php

namespace App\Service\Transformer;

use App\DTO\Transaction;
use App\Exception\Transaction\TransformationException;
use App\Validator\Json as AssertJson;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StringToTransactionTransformer
{
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    public function transform(string $jsonEncodedTransaction): Transaction
    {
        $constraints = new Assert\Sequentially([new Assert\Json(), new AssertJson\Transaction()]);
        $violations = $this->validator->validate($jsonEncodedTransaction, $constraints);

        if ($violations->count() > 0) {
            throw new TransformationException($jsonEncodedTransaction, 'Failed validation against a Transaction JSON Schema');
        }

        return $this->serializer->deserialize($jsonEncodedTransaction, Transaction::class, 'json');
    }
}