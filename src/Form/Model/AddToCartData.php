<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

final class AddToCartData
{
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(10)]
    public int $quantity = 1;
}
