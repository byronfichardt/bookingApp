<?php

namespace App\V1\Domain\dtos;

use App\V1\Application\Models\Product;
use App\V1\Application\Models\productPrice;

class ProductDto
{
    protected Product $product;
    protected ?productPrice $productPrice;

    public function __construct(Product $product, ?ProductPrice $price)
    {
        $this->product = $product;
        $this->productPrice = $price;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return productPrice|null
     */
    public function getProductPrice(): ?productPrice
    {
        return $this->productPrice;
    }
}
