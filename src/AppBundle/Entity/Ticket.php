<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 09/08/2018
 * Time: 10:01
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Traits\Common;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="ticket")
 */
class Ticket
{

    use Common;


    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tickets")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $id_user;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="tickets")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $id_client;

    /**
     * @ORM\Column(type="bigint", length=200)
     */
    private $date_sale;

    /**
     * @ORM\OneToMany(targetEntity="Ticket_detail", mappedBy="id_ticket")
     */
    private $ticket_details;

    public function __construct()
    {
        parent::__construct();
        $this->ticket_details = new ArrayCollection();
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
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param mixed $id_user
     */
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    /**
     * @return mixed
     */
    public function getIdClient()
    {
        return $this->id_client;
    }

    /**
     * @param mixed $id_client
     */
    public function setIdClient($id_client)
    {
        $this->id_client = $id_client;
    }

    /**
     * @return mixed
     */
    public function getDateSale()
    {
        return $this->date_sale;
    }

    /**
     * @param mixed $date_sale
     */
    public function setDateSale($date_sale)
    {
        $this->date_sale = $date_sale;
    }

    /**
     * @return mixed
     */
    public function getTicketDetails()
    {
        return $this->ticket_details;
    }

    /**
     * @param mixed $ticket_details
     */
    public function setTicketDetails($ticket_details)
    {
        $this->ticket_details = $ticket_details;
    }


}