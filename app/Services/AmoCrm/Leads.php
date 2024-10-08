<?php

namespace App\Services\AmoCrm;

use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\LeadModel;
use App\Services\AmoCrm\Constants\Statuses;

class Leads
{
    private LeadsCollection $leadsCollection;

    public function __construct(private ?int $contactId = null)
    {
        $this->add();
    }

    private function getContactId(): ?int
    {
        return $this->contactId;
    }

    private function setLeadsCollection(LeadsCollection $value)
    {
        $this->leadsCollection = $value;
    }

    public function getLeadsCollection(): LeadsCollection
    {
        return $this->leadsCollection;
    }

    public function getLead(): LeadModel
    {
        return $this->getLeadsCollection()->first();
    }

    private function add(): void
    {
        $leadsCollection = new LeadsCollection();
        $lead = new LeadModel();
        $contactId = $this->getContactId();
        $name = 'Сделка' . ($contactId ? ' для ' . $contactId : '');
        $lead->setName($name);
        $lead->setResponsibleUserId(Users::getRandomId());
        $leadsCollection->add($lead);

        $this->setLeadsCollection($leadsCollection);
    }

    public function save(): void
    {
        try {
            ApiClient::get()->leads()->add($this->getLeadsCollection());
        } catch (AmoCRMApiException $e) {
            throw new Exception('Could not save Lead');
            die;
        }
    }

    public static function getOne(int $leadId): LeadModel
    {
        try {
            $lead = ApiClient::get()->leads()->getOne($leadId);
        } catch (AmoCRMApiException $e) {
            throw new \Exception('Lead not founded', 404);
            die;
        }

        return $lead;
    }

    public static function successStatusNewCustomer(int $leadId, ContactModel $contact): void
    {
        $statusId = Leads::getOne($leadId)
            ->getStatusId();

        if ($statusId === Statuses::SUCCESS_ID) {
            $newCustomer = Customers::addOne();
            $contact->setIsMain(false);
            Links::link($contact, $newCustomer, EntityTypesInterface::CONTACTS);
        }
    }
}
