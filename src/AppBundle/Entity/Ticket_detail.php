<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 09/08/2018
 * Time: 10:33
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Traits\Common;


/**
 * @ORM\Entity
 * @ORM\Table(name="ticket_detail")
 */
class Ticket_detail
{

    use Common;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="ticket_details")
     * @ORM\JoinColumn(name="id_ticket", referencedColumnName="id")
     */
    private $id_ticket;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="id_product", referencedColumnName="id")
     */
    private $id_product;

    /**
     * @ORM\ManyToOne(targetEntity="Treatment")
     * @ORM\JoinColumn(name="id_treatment", referencedColumnName="id")
     */
    private $id_treatment;

    /**
     * @ORM\Column(type="integer", length=200)
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer", length=200)
     */
    private $price;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIdTicket()
    {
        return $this->id_ticket;
    }

    /**
     * @param mixed $id_ticket
     */
    public function setIdTicket($id_ticket)
    {
        $this->id_ticket = $id_ticket;
    }

    /**
     * @return mixed
     */
    public function getIdProduct()
    {
        return $this->id_product;
    }

    /**
     * @param mixed $id_product
     */
    public function setIdProduct($id_product)
    {
        $this->id_product = $id_product;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

}