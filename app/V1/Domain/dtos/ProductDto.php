<?php

namespace App\V1\Domain\dtos;

use App\V1\Application\Models\Product;
use App\V1\Application\Models\ProductPrice;

class ProductDto
{
    protected Product $product;
    protected ?ProductPrice $productPrice;
    private int $quantity;

    public function __construct(Product $product, ?ProductPrice $price, int $quantity = 1)
    {
        $this->product = $product;
        $this->productPrice = $price;
        $this->quantity = $quantity;
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

    public function getPrice()
    {
        if($this->getProductPrice()) {
            return $this->getProductPrice()->price;
        }

        return $this->getProduct()->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
