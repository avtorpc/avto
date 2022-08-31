<?php
namespace App\Library;


trait trait_CreateTableAvto
{

    public function getSQL($shcema, $table_name, $sequence_name)
    {
        return "
            CREATE SEQUENCE IF NOT EXISTS " . $shcema. '.' . $sequence_name . "
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 2147483647
                CACHE 1;
              --  OWNED BY lost.id;

            ALTER SEQUENCE " . $shcema. '.' . $sequence_name . "
                OWNER TO postgres;


            CREATE TABLE IF NOT EXISTS " . $shcema. '.' . $table_name ."
            (
                id integer NOT NULL DEFAULT nextval('" . $shcema. '.' . $sequence_name . "'::regclass)," . '
                "ID" text COLLATE pg_catalog."default",
                "MARK" text COLLATE pg_catalog."default",
                "MODEL" text COLLATE pg_catalog."default",
                "GENERATION"  text COLLATE pg_catalog."default",
                "YEAR" text COLLATE pg_catalog."default",
                "RUN" text COLLATE pg_catalog."default",
                "COLOR" text COLLATE pg_catalog."default",
                "BODY_TYPE" text COLLATE pg_catalog."default",
                "ENGINE_TYPE" text COLLATE pg_catalog."default",
                "TRANSMISSION" text COLLATE pg_catalog."default",
                "GEAR_TYPE" text COLLATE pg_catalog."default",
                "GENERATION_ID" text COLLATE pg_catalog."default",
                CONSTRAINT lost_' . $table_name . '_pkey PRIMARY KEY (id)
            ) ' . "

            TABLESPACE pg_default;

            ALTER TABLE IF EXISTS " . $shcema. '.' . $table_name . "
                OWNER to postgres;
        ";
    }

}
