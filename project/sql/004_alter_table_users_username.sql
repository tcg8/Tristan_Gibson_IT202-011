ALTER TABLE Users
    (ADD COLUMN username varchar(60) default ''
    ,UNIQUE (`email`) )
