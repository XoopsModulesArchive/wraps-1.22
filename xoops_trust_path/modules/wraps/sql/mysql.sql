# CREATE TABLE `tablename` will be queried as
# CREATE TABLE `prefix_dirname_tablename`

CREATE TABLE `indexes` (
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `title`    VARCHAR(255) NOT NULL DEFAULT '',
    `mtime`    INT(11)      NOT NULL DEFAULT 0,
    `body`     TEXT,
    PRIMARY KEY (filename)
)
    ENGINE = ISAM;

