<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 09/08/2018
 * Time: 9:44
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Traits\Common;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Client
{

    use Common;


    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $surname1;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $surname2;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $dni;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $province;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $town;

    /**
     * @ORM\Column(type="integer", length=9, nullable=true)
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="integer", length=200, nullable=true)
     */
    private $num_street;

    /**
     * @ORM\Column(type="bigint", length=200, nullable=true)
     */
    private $birthdate;

    /**
     * @ORM\Column(type="integer", length=200)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="id_client")
     */
    private $tickets;


    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname1()
    {
        return $this->surname1;
    }

    /**
     * @param mixed $surname1
     */
    public function setSurname1($surname1)
    {
        $this->surname1 = $surname1;
    }

    /**
     * @return mixed
     */
    public function getSurname2()
    {
        return $this->surname2;
    }

    /**
     * @param mixed $surname2
     */
    public function setSurname2($surname2)
    {
        $this->surname2 = $surname2;
    }

    /**
     * @return mixed
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * @param mixed $dni
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    }


    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return mixed
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param mixed $town
     */
    public function setTown($town)
    {
        $this->town = $town;
    }

    /**
     * @return mixed
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param mixed $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getNumStreet()
    {
        return $this->num_street;
    }

    /**
     * @param mixed $num_street
     */
    public function setNumStreet($num_street)
    {
        $this->num_street = $num_street;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param mixed $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @param mixed $tickets
     */
    public function setTickets($tickets)
    {
        $this->tickets = $tickets;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName() . ' ' . $this->getSurname1() . ' ' . $this->getSurname2();
    }

}