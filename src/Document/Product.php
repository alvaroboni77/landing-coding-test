<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="products")
 */
class Product
{
    /**
     * @MongoDB\Id(strategy="AUTO")
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     */
    private $asin;

    /**
     * @MongoDB\Field(type="string")
     */
    private $typeId;

    /**
     * @MongoDB\Field(type="string")
     */
    private $imageUrl;

    /**
     * @MongoDB\Field(type="string")
     */
    private $brand;

    /**
     * @MongoDB\Field(type="collection")
     */
    private $features;

    /**
     * @MongoDB\Field(type="string")
     */
    private $title;

    /**
     * @MongoDB\Field(type="bool")
     */
    private $isFreeShippingEligible;

    /**
     * @MongoDB\Field(type="float")
     */
    private $discount;

    /**
     * @MongoDB\Field(type="int")
     */
    private $salesRank;

    

    /**
     * Get the value of salesRank
     */ 
    public function getSalesRank()
    {
        return $this->salesRank;
    }

    /**
     * Set the value of salesRank
     *
     * @return  self
     */ 
    public function setSalesRank($salesRank)
    {
        $this->salesRank = $salesRank;

        return $this;
    }

    /**
     * Get the value of discount
     */ 
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set the value of discount
     *
     * @return  self
     */ 
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get the value of isFreeShippingEligible
     */ 
    public function getIsFreeShippingEligible()
    {
        return $this->isFreeShippingEligible;
    }

    /**
     * Set the value of isFreeShippingEligible
     *
     * @return  self
     */ 
    public function setIsFreeShippingEligible($isFreeShippingEligible)
    {
        $this->isFreeShippingEligible = $isFreeShippingEligible;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of features
     */ 
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set the value of features
     *
     * @return  self
     */ 
    public function setFeatures($features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * Get the value of brand
     */ 
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set the value of brand
     *
     * @return  self
     */ 
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get the value of imageUrl
     */ 
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set the value of imageUrl
     *
     * @return  self
     */ 
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get the value of typeId
     */ 
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set the value of typeId
     *
     * @return  self
     */ 
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get the value of asin
     */ 
    public function getAsin()
    {
        return $this->asin;
    }

    /**
     * Set the value of asin
     *
     * @return  self
     */ 
    public function setAsin($asin)
    {
        $this->asin = $asin;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }
}