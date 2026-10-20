CREATE TABLE `academy_investment_project`
(
    `ID`                        int          NOT NULL AUTO_INCREMENT,
    `TITLE`                     varchar(255) NOT NULL,
    `CREATED_AT`                datetime     NOT NULL,
    `CREATED_BY`                int          NOT NULL,
    `UPDATED_AT`                datetime     NOT NULL,
    `UPDATED_BY`                int          NOT NULL,
    `ESTIMATED_COMPLETION_DATE` datetime,
    `COMPLETION_DATE`           datetime,
    `DESCRIPTION`               text,
    `RESPONSIBLE_ID`            int          NOT NULL,
    `COMMENT`                   text,
    `INCOME`                    varchar(255),
    PRIMARY KEY (`ID`)
);

CREATE TABLE `academy_investment_project_history`
(
    `ID`             int          NOT NULL AUTO_INCREMENT,
    `PROJECT_ID`     int          NOT NULL,
    `AUTHOR_ID`      int          NOT NULL,
    `FIELD_NAME`     varchar(255) NOT NULL,
    `PREVIOUS_VALUE` text,
    `CURRENT_VALUE`  text,
    `CHANGED_AT`     datetime     NOT NULL,
    PRIMARY KEY (`ID`)
);