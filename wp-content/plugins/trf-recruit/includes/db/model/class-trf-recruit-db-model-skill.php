<?php
namespace TrfRecruit\Includes\DB\Model;

use \TruFetcher\Includes\DB\Model\Constants\Tru_Fetcher_DB_Model_Constants;
use \TruFetcher\Includes\DB\Model\Tru_Fetcher_DB_Model;
use TruFetcher\Includes\DB\Model\WP\Tru_Fetcher_DB_Model_WP_User;

class Trf_Recruit_DB_Model_Skill extends Tru_Fetcher_DB_Model
{

    const TABLE_NAME = 'tru_fetcher_skill';
    public string $tableName = self::TABLE_NAME;
    protected bool $dateInserts = false;

    protected array $tableConfig = [];

    private string $nameColumn = 'name';
    private string $labelColumn = 'label';

    public function __construct()
    {
        Tru_Fetcher_DB_Model::__construct();
        $this->tableConfig = [
            Tru_Fetcher_DB_Model_Constants::COLUMNS => [
                $this->getIdColumn() => 'mediumint(9) NOT NULL AUTO_INCREMENT',
                $this->getNameColumn() => 'varchar(255) NOT NULL',
                $this->getLabelColumn() => 'varchar(255) NOT NULL',
            ],
            Tru_Fetcher_DB_Model_Constants::ALIAS => 'skill',
            Tru_Fetcher_DB_Model_Constants::PRIMARY_KEY_FIELD => $this->getIdColumn(),
            Tru_Fetcher_DB_Model_Constants::UNIQUE_CONSTRAINT_FIELD => [$this->getNameColumn()],
            Tru_Fetcher_DB_Model_Constants::PIVOTS => [
                [
                    Tru_Fetcher_DB_Model_Constants::PIVOTS_TABLE => Trf_Recruit_DB_Model_User_Skill::class,
                    Tru_Fetcher_DB_Model_Constants::PIVOT_FOREIGN_TABLE => Tru_Fetcher_DB_Model_WP_User::class,
                    Tru_Fetcher_DB_Model_Constants::PIVOT_FOREIGN_KEY => (new Trf_Recruit_DB_Model_User_Skill())->getUserIdColumn(),
                    Tru_Fetcher_DB_Model_Constants::PIVOT_FOREIGN_KEY_REFERENCE => (new Tru_Fetcher_DB_Model_WP_User())->getUserIdField(),
                    Tru_Fetcher_DB_Model_Constants::PIVOT_RELATED_TABLE => self::class,
                    Tru_Fetcher_DB_Model_Constants::PIVOT_RELATED_KEY => (new Trf_Recruit_DB_Model_User_Skill())->getSkillIdColumn(),
                    Tru_Fetcher_DB_Model_Constants::PIVOT_RELATED_REF => $this->getIdColumn()
                ],
            ]
        ];
    }


    /**
     * @return string
     */
    public function getNameColumn(): string
    {
        return $this->nameColumn;
    }

    /**
     * @return string
     */
    public function getLabelColumn(): string
    {
        return $this->labelColumn;
    }

}
