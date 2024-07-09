<?php

namespace App\Services\AmoCrm;

use AmoCRM\Collections\TasksCollection;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\TaskModel;
use App\Services\Utils\MyDateTime;

class Tasks
{
    private TasksCollection $tasksCollection;

    public function __construct(
        private int $entityId,
        private int $responsibleUserId,
    ) {
        $this->add();
    }

    public function add(): void
    {
        //Создадим задачу
        $tasksCollection = new TasksCollection();
        $task = new TaskModel();
        $task->setTaskTypeId(TaskModel::TASK_TYPE_ID_MEETING)
            ->setText('Задача из API')
            ->setCompleteTill(strtotime(MyDateTime::addWorkingDays(4)))
            ->setEntityType(EntityTypesInterface::LEADS)
            ->setEntityId($this->entityId)
            ->setDuration(52 * 60) // 52 min
            ->setResponsibleUserId($this->responsibleUserId);
        $tasksCollection->add($task);

        $this->tasksCollection = $tasksCollection;
    }

    public function save(): void
    {
        ApiClient::get()->tasks()->add($this->tasksCollection);
    }
}
