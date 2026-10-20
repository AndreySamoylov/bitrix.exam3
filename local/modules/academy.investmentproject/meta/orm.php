<?php

/* ORMENTITYANNOTATION:Academy\InvestmentProject\InvestmentProjectTable */
namespace Academy\InvestmentProject {
	/**
	 * EO_InvestmentProject
	 * @see \Academy\InvestmentProject\InvestmentProjectTable
	 *
	 * Custom methods:
	 * ---------------
	 *
	 * @method \int getId()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setId(\int|\Bitrix\Main\DB\SqlExpression $id)
	 * @method bool hasId()
	 * @method bool isIdFilled()
	 * @method bool isIdChanged()
	 * @method \string getTitle()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setTitle(\string|\Bitrix\Main\DB\SqlExpression $title)
	 * @method bool hasTitle()
	 * @method bool isTitleFilled()
	 * @method bool isTitleChanged()
	 * @method \string remindActualTitle()
	 * @method \string requireTitle()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject resetTitle()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unsetTitle()
	 * @method \string fillTitle()
	 * @method \Bitrix\Main\Type\DateTime getCreatedAt()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setCreatedAt(\Bitrix\Main\Type\DateTime|\Bitrix\Main\DB\SqlExpression $createdAt)
	 * @method bool hasCreatedAt()
	 * @method bool isCreatedAtFilled()
	 * @method bool isCreatedAtChanged()
	 * @method \Bitrix\Main\Type\DateTime remindActualCreatedAt()
	 * @method \Bitrix\Main\Type\DateTime requireCreatedAt()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject resetCreatedAt()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unsetCreatedAt()
	 * @method \Bitrix\Main\Type\DateTime fillCreatedAt()
	 * @method \int getCreatedBy()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setCreatedBy(\int|\Bitrix\Main\DB\SqlExpression $createdBy)
	 * @method bool hasCreatedBy()
	 * @method bool isCreatedByFilled()
	 * @method bool isCreatedByChanged()
	 * @method \int remindActualCreatedBy()
	 * @method \int requireCreatedBy()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject resetCreatedBy()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unsetCreatedBy()
	 * @method \int fillCreatedBy()
	 * @method \Bitrix\Main\Type\DateTime getUpdatedAt()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setUpdatedAt(\Bitrix\Main\Type\DateTime|\Bitrix\Main\DB\SqlExpression $updatedAt)
	 * @method bool hasUpdatedAt()
	 * @method bool isUpdatedAtFilled()
	 * @method bool isUpdatedAtChanged()
	 * @method \Bitrix\Main\Type\DateTime remindActualUpdatedAt()
	 * @method \Bitrix\Main\Type\DateTime requireUpdatedAt()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject resetUpdatedAt()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unsetUpdatedAt()
	 * @method \Bitrix\Main\Type\DateTime fillUpdatedAt()
	 * @method \int getUpdatedBy()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setUpdatedBy(\int|\Bitrix\Main\DB\SqlExpression $updatedBy)
	 * @method bool hasUpdatedBy()
	 * @method bool isUpdatedByFilled()
	 * @method bool isUpdatedByChanged()
	 * @method \int remindActualUpdatedBy()
	 * @method \int requireUpdatedBy()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject resetUpdatedBy()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unsetUpdatedBy()
	 * @method \int fillUpdatedBy()
	 * @method \Bitrix\Main\Type\DateTime getEstimatedCompletionDate()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setEstimatedCompletionDate(\Bitrix\Main\Type\DateTime|\Bitrix\Main\DB\SqlExpression $estimatedCompletionDate)
	 * @method bool hasEstimatedCompletionDate()
	 * @method bool isEstimatedCompletionDateFilled()
	 * @method bool isEstimatedCompletionDateChanged()
	 * @method \Bitrix\Main\Type\DateTime remindActualEstimatedCompletionDate()
	 * @method \Bitrix\Main\Type\DateTime requireEstimatedCompletionDate()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject resetEstimatedCompletionDate()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unsetEstimatedCompletionDate()
	 * @method \Bitrix\Main\Type\DateTime fillEstimatedCompletionDate()
	 * @method \Bitrix\Main\Type\DateTime getCompletionDate()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setCompletionDate(\Bitrix\Main\Type\DateTime|\Bitrix\Main\DB\SqlExpression $completionDate)
	 * @method bool hasCompletionDate()
	 * @method bool isCompletionDateFilled()
	 * @method bool isCompletionDateChanged()
	 * @method \Bitrix\Main\Type\DateTime remindActualCompletionDate()
	 * @method \Bitrix\Main\Type\DateTime requireCompletionDate()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject resetCompletionDate()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unsetCompletionDate()
	 * @method \Bitrix\Main\Type\DateTime fillCompletionDate()
	 * @method \string getDescription()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setDescription(\string|\Bitrix\Main\DB\SqlExpression $description)
	 * @method bool hasDescription()
	 * @method bool isDescriptionFilled()
	 * @method bool isDescriptionChanged()
	 * @method \string remindActualDescription()
	 * @method \string requireDescription()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject resetDescription()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unsetDescription()
	 * @method \string fillDescription()
	 * @method \int getResponsibleId()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setResponsibleId(\int|\Bitrix\Main\DB\SqlExpression $responsibleId)
	 * @method bool hasResponsibleId()
	 * @method bool isResponsibleIdFilled()
	 * @method bool isResponsibleIdChanged()
	 * @method \int remindActualResponsibleId()
	 * @method \int requireResponsibleId()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject resetResponsibleId()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unsetResponsibleId()
	 * @method \int fillResponsibleId()
	 * @method \string getComment()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setComment(\string|\Bitrix\Main\DB\SqlExpression $comment)
	 * @method bool hasComment()
	 * @method bool isCommentFilled()
	 * @method bool isCommentChanged()
	 * @method \string remindActualComment()
	 * @method \string requireComment()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject resetComment()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unsetComment()
	 * @method \string fillComment()
	 * @method \string getIncome()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject setIncome(\string|\Bitrix\Main\DB\SqlExpression $income)
	 * @method bool hasIncome()
	 * @method bool isIncomeFilled()
	 * @method bool isIncomeChanged()
	 * @method \string remindActualIncome()
	 * @method \string requireIncome()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject resetIncome()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unsetIncome()
	 * @method \string fillIncome()
	 *
	 * Common methods:
	 * ---------------
	 *
	 * @property-read \Bitrix\Main\ORM\Entity $entity
	 * @property-read array $primary
	 * @property-read int $state @see \Bitrix\Main\ORM\Objectify\State
	 * @property-read \Bitrix\Main\Type\Dictionary $customData
	 * @property \Bitrix\Main\Authentication\Context $authContext
	 * @method mixed get($fieldName)
	 * @method mixed remindActual($fieldName)
	 * @method mixed require($fieldName)
	 * @method bool has($fieldName)
	 * @method bool isFilled($fieldName)
	 * @method bool isChanged($fieldName)
	 * @method \Academy\InvestmentProject\EO_InvestmentProject set($fieldName, $value)
	 * @method \Academy\InvestmentProject\EO_InvestmentProject reset($fieldName)
	 * @method \Academy\InvestmentProject\EO_InvestmentProject unset($fieldName)
	 * @method void addTo($fieldName, $value)
	 * @method void removeFrom($fieldName, $value)
	 * @method void removeAll($fieldName)
	 * @method \Bitrix\Main\ORM\Data\Result delete()
	 * @method void fill($fields = \Bitrix\Main\ORM\Fields\FieldTypeMask::ALL) flag or array of field names
	 * @method mixed[] collectValues($valuesType = \Bitrix\Main\ORM\Objectify\Values::ALL, $fieldsMask = \Bitrix\Main\ORM\Fields\FieldTypeMask::ALL)
	 * @method \Bitrix\Main\ORM\Data\AddResult|\Bitrix\Main\ORM\Data\UpdateResult|\Bitrix\Main\ORM\Data\Result save()
	 * @method static \Academy\InvestmentProject\EO_InvestmentProject wakeUp($data)
	 */
	class EO_InvestmentProject {
		/* @var \Academy\InvestmentProject\InvestmentProjectTable */
		static public $dataClass = '\Academy\InvestmentProject\InvestmentProjectTable';
		/**
		 * @param bool|array $setDefaultValues
		 */
		public function __construct($setDefaultValues = true) {}
	}
}
namespace Academy\InvestmentProject {
	/**
	 * EO_InvestmentProject_Collection
	 *
	 * Custom methods:
	 * ---------------
	 *
	 * @method \int[] getIdList()
	 * @method \string[] getTitleList()
	 * @method \string[] fillTitle()
	 * @method \Bitrix\Main\Type\DateTime[] getCreatedAtList()
	 * @method \Bitrix\Main\Type\DateTime[] fillCreatedAt()
	 * @method \int[] getCreatedByList()
	 * @method \int[] fillCreatedBy()
	 * @method \Bitrix\Main\Type\DateTime[] getUpdatedAtList()
	 * @method \Bitrix\Main\Type\DateTime[] fillUpdatedAt()
	 * @method \int[] getUpdatedByList()
	 * @method \int[] fillUpdatedBy()
	 * @method \Bitrix\Main\Type\DateTime[] getEstimatedCompletionDateList()
	 * @method \Bitrix\Main\Type\DateTime[] fillEstimatedCompletionDate()
	 * @method \Bitrix\Main\Type\DateTime[] getCompletionDateList()
	 * @method \Bitrix\Main\Type\DateTime[] fillCompletionDate()
	 * @method \string[] getDescriptionList()
	 * @method \string[] fillDescription()
	 * @method \int[] getResponsibleIdList()
	 * @method \int[] fillResponsibleId()
	 * @method \string[] getCommentList()
	 * @method \string[] fillComment()
	 * @method \string[] getIncomeList()
	 * @method \string[] fillIncome()
	 *
	 * Common methods:
	 * ---------------
	 *
	 * @property-read \Bitrix\Main\ORM\Entity $entity
	 * @method void add(\Academy\InvestmentProject\EO_InvestmentProject $object)
	 * @method bool has(\Academy\InvestmentProject\EO_InvestmentProject $object)
	 * @method bool hasByPrimary($primary)
	 * @method \Academy\InvestmentProject\EO_InvestmentProject getByPrimary($primary)
	 * @method \Academy\InvestmentProject\EO_InvestmentProject[] getAll()
	 * @method bool remove(\Academy\InvestmentProject\EO_InvestmentProject $object)
	 * @method void removeByPrimary($primary)
	 * @method void fill($fields = \Bitrix\Main\ORM\Fields\FieldTypeMask::ALL) flag or array of field names
	 * @method static \Academy\InvestmentProject\EO_InvestmentProject_Collection wakeUp($data)
	 * @method \Bitrix\Main\ORM\Data\Result save($ignoreEvents = false)
	 * @method void offsetSet() ArrayAccess
	 * @method void offsetExists() ArrayAccess
	 * @method void offsetUnset() ArrayAccess
	 * @method void offsetGet() ArrayAccess
	 * @method void rewind() Iterator
	 * @method \Academy\InvestmentProject\EO_InvestmentProject current() Iterator
	 * @method mixed key() Iterator
	 * @method void next() Iterator
	 * @method bool valid() Iterator
	 * @method int count() Countable
	 * @method EO_InvestmentProject_Collection merge(?EO_InvestmentProject_Collection $collection)
	 * @method bool isEmpty()
	 */
	class EO_InvestmentProject_Collection implements \ArrayAccess, \Iterator, \Countable {
		/* @var \Academy\InvestmentProject\InvestmentProjectTable */
		static public $dataClass = '\Academy\InvestmentProject\InvestmentProjectTable';
	}
}
namespace Academy\InvestmentProject {
	/**
	 * @method static EO_InvestmentProject_Query query()
	 * @method static EO_InvestmentProject_Result getByPrimary($primary, array $parameters = [])
	 * @method static EO_InvestmentProject_Result getById($id)
	 * @method static EO_InvestmentProject_Result getList(array $parameters = [])
	 * @method static EO_InvestmentProject_Entity getEntity()
	 * @method static \Academy\InvestmentProject\EO_InvestmentProject createObject($setDefaultValues = true)
	 * @method static \Academy\InvestmentProject\EO_InvestmentProject_Collection createCollection()
	 * @method static \Academy\InvestmentProject\EO_InvestmentProject wakeUpObject($row)
	 * @method static \Academy\InvestmentProject\EO_InvestmentProject_Collection wakeUpCollection($rows)
	 */
	class InvestmentProjectTable extends \Bitrix\Main\ORM\Data\DataManager {}
	/**
	 * Common methods:
	 * ---------------
	 *
	 * @method EO_InvestmentProject_Result exec()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject fetchObject()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject_Collection fetchCollection()
	 *
	 * Custom methods:
	 * ---------------
	 *
	 */
	class EO_InvestmentProject_Query extends \Bitrix\Main\ORM\Query\Query {}
	/**
	 * @method \Academy\InvestmentProject\EO_InvestmentProject fetchObject()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject_Collection fetchCollection()
	 */
	class EO_InvestmentProject_Result extends \Bitrix\Main\ORM\Query\Result {}
	/**
	 * @method \Academy\InvestmentProject\EO_InvestmentProject createObject($setDefaultValues = true)
	 * @method \Academy\InvestmentProject\EO_InvestmentProject_Collection createCollection()
	 * @method \Academy\InvestmentProject\EO_InvestmentProject wakeUpObject($row)
	 * @method \Academy\InvestmentProject\EO_InvestmentProject_Collection wakeUpCollection($rows)
	 */
	class EO_InvestmentProject_Entity extends \Bitrix\Main\ORM\Entity {}
}
/* ORMENTITYANNOTATION:Academy\InvestmentProject\History\HistoryTable */
namespace Academy\InvestmentProject\History {
	/**
	 * EO_History
	 * @see \Academy\InvestmentProject\History\HistoryTable
	 *
	 * Custom methods:
	 * ---------------
	 *
	 * @method \int getId()
	 * @method \Academy\InvestmentProject\History\EO_History setId(\int|\Bitrix\Main\DB\SqlExpression $id)
	 * @method bool hasId()
	 * @method bool isIdFilled()
	 * @method bool isIdChanged()
	 * @method \int getProjectId()
	 * @method \Academy\InvestmentProject\History\EO_History setProjectId(\int|\Bitrix\Main\DB\SqlExpression $projectId)
	 * @method bool hasProjectId()
	 * @method bool isProjectIdFilled()
	 * @method bool isProjectIdChanged()
	 * @method \int remindActualProjectId()
	 * @method \int requireProjectId()
	 * @method \Academy\InvestmentProject\History\EO_History resetProjectId()
	 * @method \Academy\InvestmentProject\History\EO_History unsetProjectId()
	 * @method \int fillProjectId()
	 * @method \int getAuthorId()
	 * @method \Academy\InvestmentProject\History\EO_History setAuthorId(\int|\Bitrix\Main\DB\SqlExpression $authorId)
	 * @method bool hasAuthorId()
	 * @method bool isAuthorIdFilled()
	 * @method bool isAuthorIdChanged()
	 * @method \int remindActualAuthorId()
	 * @method \int requireAuthorId()
	 * @method \Academy\InvestmentProject\History\EO_History resetAuthorId()
	 * @method \Academy\InvestmentProject\History\EO_History unsetAuthorId()
	 * @method \int fillAuthorId()
	 * @method \string getFieldName()
	 * @method \Academy\InvestmentProject\History\EO_History setFieldName(\string|\Bitrix\Main\DB\SqlExpression $fieldName)
	 * @method bool hasFieldName()
	 * @method bool isFieldNameFilled()
	 * @method bool isFieldNameChanged()
	 * @method \string remindActualFieldName()
	 * @method \string requireFieldName()
	 * @method \Academy\InvestmentProject\History\EO_History resetFieldName()
	 * @method \Academy\InvestmentProject\History\EO_History unsetFieldName()
	 * @method \string fillFieldName()
	 * @method \string getPreviousValue()
	 * @method \Academy\InvestmentProject\History\EO_History setPreviousValue(\string|\Bitrix\Main\DB\SqlExpression $previousValue)
	 * @method bool hasPreviousValue()
	 * @method bool isPreviousValueFilled()
	 * @method bool isPreviousValueChanged()
	 * @method \string remindActualPreviousValue()
	 * @method \string requirePreviousValue()
	 * @method \Academy\InvestmentProject\History\EO_History resetPreviousValue()
	 * @method \Academy\InvestmentProject\History\EO_History unsetPreviousValue()
	 * @method \string fillPreviousValue()
	 * @method \string getCurrentValue()
	 * @method \Academy\InvestmentProject\History\EO_History setCurrentValue(\string|\Bitrix\Main\DB\SqlExpression $currentValue)
	 * @method bool hasCurrentValue()
	 * @method bool isCurrentValueFilled()
	 * @method bool isCurrentValueChanged()
	 * @method \string remindActualCurrentValue()
	 * @method \string requireCurrentValue()
	 * @method \Academy\InvestmentProject\History\EO_History resetCurrentValue()
	 * @method \Academy\InvestmentProject\History\EO_History unsetCurrentValue()
	 * @method \string fillCurrentValue()
	 * @method \Bitrix\Main\Type\DateTime getChangedAt()
	 * @method \Academy\InvestmentProject\History\EO_History setChangedAt(\Bitrix\Main\Type\DateTime|\Bitrix\Main\DB\SqlExpression $changedAt)
	 * @method bool hasChangedAt()
	 * @method bool isChangedAtFilled()
	 * @method bool isChangedAtChanged()
	 * @method \Bitrix\Main\Type\DateTime remindActualChangedAt()
	 * @method \Bitrix\Main\Type\DateTime requireChangedAt()
	 * @method \Academy\InvestmentProject\History\EO_History resetChangedAt()
	 * @method \Academy\InvestmentProject\History\EO_History unsetChangedAt()
	 * @method \Bitrix\Main\Type\DateTime fillChangedAt()
	 *
	 * Common methods:
	 * ---------------
	 *
	 * @property-read \Bitrix\Main\ORM\Entity $entity
	 * @property-read array $primary
	 * @property-read int $state @see \Bitrix\Main\ORM\Objectify\State
	 * @property-read \Bitrix\Main\Type\Dictionary $customData
	 * @property \Bitrix\Main\Authentication\Context $authContext
	 * @method mixed get($fieldName)
	 * @method mixed remindActual($fieldName)
	 * @method mixed require($fieldName)
	 * @method bool has($fieldName)
	 * @method bool isFilled($fieldName)
	 * @method bool isChanged($fieldName)
	 * @method \Academy\InvestmentProject\History\EO_History set($fieldName, $value)
	 * @method \Academy\InvestmentProject\History\EO_History reset($fieldName)
	 * @method \Academy\InvestmentProject\History\EO_History unset($fieldName)
	 * @method void addTo($fieldName, $value)
	 * @method void removeFrom($fieldName, $value)
	 * @method void removeAll($fieldName)
	 * @method \Bitrix\Main\ORM\Data\Result delete()
	 * @method void fill($fields = \Bitrix\Main\ORM\Fields\FieldTypeMask::ALL) flag or array of field names
	 * @method mixed[] collectValues($valuesType = \Bitrix\Main\ORM\Objectify\Values::ALL, $fieldsMask = \Bitrix\Main\ORM\Fields\FieldTypeMask::ALL)
	 * @method \Bitrix\Main\ORM\Data\AddResult|\Bitrix\Main\ORM\Data\UpdateResult|\Bitrix\Main\ORM\Data\Result save()
	 * @method static \Academy\InvestmentProject\History\EO_History wakeUp($data)
	 */
	class EO_History {
		/* @var \Academy\InvestmentProject\History\HistoryTable */
		static public $dataClass = '\Academy\InvestmentProject\History\HistoryTable';
		/**
		 * @param bool|array $setDefaultValues
		 */
		public function __construct($setDefaultValues = true) {}
	}
}
namespace Academy\InvestmentProject\History {
	/**
	 * EO_History_Collection
	 *
	 * Custom methods:
	 * ---------------
	 *
	 * @method \int[] getIdList()
	 * @method \int[] getProjectIdList()
	 * @method \int[] fillProjectId()
	 * @method \int[] getAuthorIdList()
	 * @method \int[] fillAuthorId()
	 * @method \string[] getFieldNameList()
	 * @method \string[] fillFieldName()
	 * @method \string[] getPreviousValueList()
	 * @method \string[] fillPreviousValue()
	 * @method \string[] getCurrentValueList()
	 * @method \string[] fillCurrentValue()
	 * @method \Bitrix\Main\Type\DateTime[] getChangedAtList()
	 * @method \Bitrix\Main\Type\DateTime[] fillChangedAt()
	 *
	 * Common methods:
	 * ---------------
	 *
	 * @property-read \Bitrix\Main\ORM\Entity $entity
	 * @method void add(\Academy\InvestmentProject\History\EO_History $object)
	 * @method bool has(\Academy\InvestmentProject\History\EO_History $object)
	 * @method bool hasByPrimary($primary)
	 * @method \Academy\InvestmentProject\History\EO_History getByPrimary($primary)
	 * @method \Academy\InvestmentProject\History\EO_History[] getAll()
	 * @method bool remove(\Academy\InvestmentProject\History\EO_History $object)
	 * @method void removeByPrimary($primary)
	 * @method void fill($fields = \Bitrix\Main\ORM\Fields\FieldTypeMask::ALL) flag or array of field names
	 * @method static \Academy\InvestmentProject\History\EO_History_Collection wakeUp($data)
	 * @method \Bitrix\Main\ORM\Data\Result save($ignoreEvents = false)
	 * @method void offsetSet() ArrayAccess
	 * @method void offsetExists() ArrayAccess
	 * @method void offsetUnset() ArrayAccess
	 * @method void offsetGet() ArrayAccess
	 * @method void rewind() Iterator
	 * @method \Academy\InvestmentProject\History\EO_History current() Iterator
	 * @method mixed key() Iterator
	 * @method void next() Iterator
	 * @method bool valid() Iterator
	 * @method int count() Countable
	 * @method EO_History_Collection merge(?EO_History_Collection $collection)
	 * @method bool isEmpty()
	 */
	class EO_History_Collection implements \ArrayAccess, \Iterator, \Countable {
		/* @var \Academy\InvestmentProject\History\HistoryTable */
		static public $dataClass = '\Academy\InvestmentProject\History\HistoryTable';
	}
}
namespace Academy\InvestmentProject\History {
	/**
	 * @method static EO_History_Query query()
	 * @method static EO_History_Result getByPrimary($primary, array $parameters = [])
	 * @method static EO_History_Result getById($id)
	 * @method static EO_History_Result getList(array $parameters = [])
	 * @method static EO_History_Entity getEntity()
	 * @method static \Academy\InvestmentProject\History\EO_History createObject($setDefaultValues = true)
	 * @method static \Academy\InvestmentProject\History\EO_History_Collection createCollection()
	 * @method static \Academy\InvestmentProject\History\EO_History wakeUpObject($row)
	 * @method static \Academy\InvestmentProject\History\EO_History_Collection wakeUpCollection($rows)
	 */
	class HistoryTable extends \Bitrix\Main\ORM\Data\DataManager {}
	/**
	 * Common methods:
	 * ---------------
	 *
	 * @method EO_History_Result exec()
	 * @method \Academy\InvestmentProject\History\EO_History fetchObject()
	 * @method \Academy\InvestmentProject\History\EO_History_Collection fetchCollection()
	 *
	 * Custom methods:
	 * ---------------
	 *
	 */
	class EO_History_Query extends \Bitrix\Main\ORM\Query\Query {}
	/**
	 * @method \Academy\InvestmentProject\History\EO_History fetchObject()
	 * @method \Academy\InvestmentProject\History\EO_History_Collection fetchCollection()
	 */
	class EO_History_Result extends \Bitrix\Main\ORM\Query\Result {}
	/**
	 * @method \Academy\InvestmentProject\History\EO_History createObject($setDefaultValues = true)
	 * @method \Academy\InvestmentProject\History\EO_History_Collection createCollection()
	 * @method \Academy\InvestmentProject\History\EO_History wakeUpObject($row)
	 * @method \Academy\InvestmentProject\History\EO_History_Collection wakeUpCollection($rows)
	 */
	class EO_History_Entity extends \Bitrix\Main\ORM\Entity {}
}