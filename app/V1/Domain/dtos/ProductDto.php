<?php

namespace App\V1\Domain\dtos;

use App\V1\Application\Models\Product;
use App\V1\Application\Models\ProductPrice;

class ProductDto
{
    protected Product $product;
    protected ?ProductPrice $productPrice;

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
     * @return ProductPrice|null
     */
    public function getProductPrice(): ?ProductPrice
    {
        return $this->productPrice;
    }
}
