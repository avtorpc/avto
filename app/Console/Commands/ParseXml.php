<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Library\MyIterator;
use Illuminate\Support\Facades\DB;


class ParseXml extends Command
{

    use \App\Library\trait_CreateTableAvto;

    private $filename = 'data.xml';
    private $tag_ignore = ['AUTO-CATALOG', 'OFFER', 'OFFERS'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name{filename?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск парсера остатков';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $args = $this->argument('filename');
        $d_t = new \DateTime();
        $table_name =  "lost_" . $d_t->getTimestamp();
        $sequence_name = "lost_id_seq_" . $d_t->getTimestamp();
        $sql = $this->getSQL("avto_xml", $table_name, $sequence_name);

        DB::unprepared($sql);

        if (count($args)) {
            $filename = $args[0];
        }

        $sql = '
           INSERT INTO ' . 'avto_xml' . '.' . $table_name . '(
             "ID", "MARK", "MODEL", "GENERATION", "YEAR", "RUN", "COLOR", "BODY_TYPE", "ENGINE_TYPE", "TRANSMISSION", "GEAR_TYPE", "GENERATION_ID")
            VALUES (:ID, :MARK, :MODEL, :GENERATION, :YEAR, :RUN, :COLOR, :BODY_TYPE, :ENGINE_TYPE, :TRANSMISSION, :GEAR_TYPE, :GENERATION_ID);
        ';

        $file = realpath(__DIR__ . "/../../../" . env('PARSE_DIR') . '/' . $filename);
        $simple = file_get_contents($file);
        $p = xml_parser_create();
        xml_parse_into_struct($p, $simple, $vals, $index);
        xml_parser_free($p);
        $arr_list = [];

        $iterator = new MyIterator($index['OFFERS']);

        while ($iterator->valid()) {
            $b = $iterator->current();
            $iterator->next();
            if ($iterator->valid()) {
                for ($i = $b; $i < $iterator->current(); $i++) {
                    if (!in_array($vals[$i]['tag'], $this->tag_ignore)) {
                        $tag_name = ':' . str_replace('-', "_", $vals[$i]['tag'] );
                        if (isset($vals[$i]['value'])) {
                            $arr_list[$tag_name] = $vals[$i]['value'];
                        } else {
                            $arr_list[$tag_name] = '';
                        }

                    }
                }

                DB::insert(
                    $sql, $arr_list
                );
            }
        }


    }
}

