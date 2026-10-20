<?php

namespace Academy\InvestmentProject;

use Bitrix\Main\Type\DateTime;

final class Project
{
    /**
     * @param int|null $id Идентификатор
     * @param string $title Название
     * @param DateTime|null $createdAt Когда создано
     * @param int|null $createdBy Кем создано
     * @param DateTime|null $updatedAt Когда изменено
     * @param int|null $updatedBy Кем изменено
     * @param DateTime|null $estimatedCompletionDate Ожидаемая дата завершения
     * @param DateTime|null $completionDate Дата завершения
     * @param string $description Описание
     * @param int $responsibleId Ответственный
     * @param string $comment Комментарий
     * @param string $income Доход
     */
    public function __construct(
        public readonly ?int $id,
        public string $title,
        public ?DateTime $createdAt,
        public ?int $createdBy,
        public ?DateTime $updatedAt,
        public ?int $updatedBy,
        public ?DateTime $estimatedCompletionDate,
        public ?DateTime $completionDate,
        public string $description,
        public int $responsibleId,
        public string $comment,
        public string $income
    ) {
    }

    public function withId(int $id): Project
    {
        return new Project(
            $id,
            $this->title,
            $this->createdAt,
            $this->createdBy,
            $this->updatedAt,
            $this->updatedBy,
            $this->estimatedCompletionDate,
            $this->completionDate,
            $this->description,
            $this->responsibleId,
            $this->comment,
            $this->income
        );
    }
}