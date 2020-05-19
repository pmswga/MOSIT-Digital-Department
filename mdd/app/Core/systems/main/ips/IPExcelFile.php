<?php


namespace App\Core\Systems\Main\IPS;
use PhpOffice\PhpSpreadsheet\IOFactory;

// #todo Переписать документацию
// #fixme Переписать парсер документа и подумать, как реализовать более компактно и проще в реализации
// #fixme следует разработать в виде паттерна Builder


/**
 * @class IPExcelFile
 * @brief Реализует обработку файлов ИП в формате Excel для получения данных
 *
 * @package App\Core\Systems\Main\IPS
 */
class IPExcelFile
{
    /**
     * @var array $ipExcelConfig - содержит набор Excel-адресов клеток на данные в ИП
     */
    private $ipExcelConfig = [
        0 => [
            'startYearA' => 'BW12',
            'startYearB' => 'CA12',
            'endYearA' => 'FE12',
            'endYearB' => 'FI12',

            'institute' => 'A26',
            'faculty'   => 'CJ26',
            'teacherPost' => 'Q27',
            'degree'    => 'AH28',
            'initials'  => 'A29'
        ],
        1 => [
            'subjects' => '6'
        ],
        2 => [
            'subjects' => '5'
        ],
        3 => [
            'work' => '24'
        ],
        4 => [
            'work' => '4'
        ],
        5 => [
            'work' => '3'
        ],
        6 => [
            'workSum1' => 0,
            'workSum2' => 0,
            'workSum3' => 0,
            'sum' => 0
        ]
    ];

    /**
     * @var \PhpOffice\PhpSpreadsheet\Spreadsheet|null $file - является текущим файлом Excel документа
     */
    private $file;

    /**
     * @var array $data - содержит полученные данные из Excel документа
     */
    private $data;

    /**
     * По содержимому Excel файла загружает из него листы для дальнейшего парсинга
     *
     * @param $file
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function __construct($file)
    {
        $this->file = $this->loadFile($file);
        $this->data = [
            1 => [],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
            6 => []
        ];

        $this->parseData();
        $this->prepareData();
        $this->calculateResult();
    }

    /**
     * Возвращает текущий файл Excel доступный для чтения
     *
     * @param $file - содержимое файла Excel документа
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet|null
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function loadFile($file) {
        $tmpFile = null;

        $fp = tmpfile();
        fputs($fp, $file);
        $tmpFile = IOFactory::load(stream_get_meta_data($fp)['uri']);
        fclose($fp);

        return $tmpFile;
    }

    private function parseSheet1($sheet, $cellKey, $stopText) {

        $value = ' ';
        $_cell = $this->ipExcelConfig[$sheet][$cellKey];

        while (!preg_match('/ИТОГО/i', $value)) {
            $value = $this->file->getSheet($sheet)->getCellByColumnAndRow(1, $_cell)->getValue();
            if (!empty($value) && $value != $stopText) {
                $this->data[$sheet][$cellKey][] = $value;
            }

            $_cell += 2;
        }

        $this->data[$sheet]['sum'] = $this->file->getSheet($sheet)->getCell('X'.($_cell-2))->getCalculatedValue();
    }

    /**
     * Получает данные из каждого листа Excel документа
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function parseData() {
        foreach ($this->ipExcelConfig as $sheet => $cells) {
            foreach ($cells as $cellKey => $cell) {
                switch ($sheet) {
                    case 0:
                    {
                        $this->data[$sheet][$cellKey] = $this->file->getSheet($sheet)->getCell($cell)->getValue();
                    } break;
                    case 1:
                    {
                        $this->data[$sheet][$cellKey] = [];
                        $this->parseSheet1($sheet, $cellKey, 'ИТОГО ЗА ОСЕННИЙ СЕМЕСТР');
                    } break;
                    case 2:
                    {
                        $this->data[$sheet][$cellKey] = [];
                        $this->parseSheet1($sheet, $cellKey, 'ИТОГО ЗА ВЕСЕННИЙ СЕМЕСТР');
                    } break;
                    case 3:
                    {
                        $workNum = ' ';
                        $_cell = $this->ipExcelConfig[$sheet][$cellKey];
                        $this->data[$sheet][$cellKey] = [];

                        while ($workNum) {
                            $workNum = $this->file->getSheet($sheet)->getCellByColumnAndRow(1, $_cell)->getValue();
                            $workCaption = $this->file->getSheet($sheet)->getCellByColumnAndRow(2, $_cell)->getValue();
                            $workTimePlan = $this->file->getSheet($sheet)->getCell('D'.$_cell)->getValue();
                            $workTimeReal = $this->file->getSheet($sheet)->getCell('E'.$_cell)->getValue();
                            $workFinish = $this->file->getSheet($sheet)->getCell('G'.$_cell)->getValue();
                            $workFinishDatePlan = $this->file->getSheet($sheet)->getCell('N'.$_cell)->getFormattedValue();
                            $workFinishDateReal = $this->file->getSheet($sheet)->getCell('T'.$_cell)->getFormattedValue();

                            if ($workNum && $workNum != 'ИТОГО') {
                                $this->data[$sheet][$cellKey][] = [
                                    'num' => $workNum,
                                    'caption' => $workCaption,
                                    'plan' => $workTimePlan,
                                    'real' => $workTimeReal,
                                    'finish' => $workFinish,
                                    'finishDatePlan' => $workFinishDatePlan,
                                    'finishDateReal' => $workFinishDateReal
                                ];
                            }

                            $_cell += 1;
                        }
                    } break;
                    case 4:
                    {
                        $workNum = ' ';
                        $_cell = $this->ipExcelConfig[$sheet][$cellKey];
                        $this->data[$sheet][$cellKey] = [];

                        while ($workNum) {
                            $workNum = $this->file->getSheet($sheet)->getCell('A'.$_cell)->getValue();
                            $workCaption = $this->file->getSheet($sheet)->getCell('B'.$_cell)->getValue();
                            $workTimePlan = $this->file->getSheet($sheet)->getCell('C'.$_cell)->getValue();
                            $workTimeReal = $this->file->getSheet($sheet)->getCell('D'.$_cell)->getValue();
                            $workFinishDatePlan = $this->file->getSheet($sheet)->getCell('E'.$_cell)->getFormattedValue();
                            $workFinishDateReal = $this->file->getSheet($sheet)->getCell('F'.$_cell)->getFormattedValue();

                            if ($workCaption && $workNum != 'ИТОГО:') {
                                $this->data[$sheet][$cellKey][] = [
                                    'num' => $workNum,
                                    'caption' => $workCaption,
                                    'plan' => $workTimePlan,
                                    'real' => $workTimeReal,
                                    'finishDatePlan' => $workFinishDatePlan,
                                    'finishDateReal' => $workFinishDateReal
                                ];
                            }

                            $_cell += 1;
                        }

                        $this->ipExcelConfig[5]['work'] += $_cell;
                    } break;
                    case 5:
                    {
                        $workNum = ' ';
                        $_cell = $this->ipExcelConfig[5][$cellKey];
                        $this->data[$sheet][$cellKey] = [];

                        while ($workNum) {
                            $workNum = $this->file->getSheet(4)->getCell('A'.$_cell)->getValue();
                            $workCaption = $this->file->getSheet(4)->getCell('B'.$_cell)->getValue();
                            $workTimePlan = $this->file->getSheet(4)->getCell('C'.$_cell)->getValue();
                            $workTimeReal = $this->file->getSheet(4)->getCell('D'.$_cell)->getValue();
                            $workFinishPlan = $this->file->getSheet(4)->getCell('E'.$_cell)->getFormattedValue();
                            $workFinishReal = $this->file->getSheet(4)->getCell('F'.$_cell)->getFormattedValue();

                            if ($workCaption && $workNum != 'ИТОГО:') {
                                $this->data[$sheet][$cellKey][] = [
                                    'num' => $workNum,
                                    'caption' => $workCaption,
                                    'plan' => $workTimePlan,
                                    'real' => $workTimeReal,
                                    'finishPlan' => $workFinishPlan,
                                    'finishReal' => $workFinishReal
                                ];
                            }

                            $_cell += 1;
                        }
                    } break;
                }
            }
        }
    }


    /**
     * Подготовка данных после парсинга Excel файла
     *
     * @return void
     */
    private function prepareData() {
        $basePost = $this->data[0]['teacherPost'];

        $post = '';
        preg_match('/Старший преподаватель|Ассистент|Преподаватель|Доцент|Профессор/i', $this->data[0]['teacherPost'], $matches);
        if ($matches) {
            $post = $matches[0];
        }

        $rateType = '';
        preg_match('/штатный|внешний/', $this->data[0]['teacherPost'], $matches);
        if ($matches) {
            $rateType = $matches[0];
        }

        $rateValue = 0;
        preg_match('/[0|1][,|.][0-9][0|5]/', $this->data[0]['teacherPost'], $matches);
        if ($matches) {
            $rateValue = $matches[0];
        }

        $initials = explode(' ', $this->data[0]['initials']);

        $secondName = $initials[0];
        $firstName = $initials[1];
        $patronymic = '';
        if (array_key_exists(2, $initials)) {
            $patronymic = $initials[2];
        }


        $this->data[0]['basePost'] = $basePost;
        $this->data[0]['teacherPost'] = $post;
        $this->data[0]['rateType'] = $rateType;
        $this->data[0]['rateValue'] = $rateValue;
        $this->data[0]['secondName'] = $secondName;
        $this->data[0]['firstName'] = $firstName;
        $this->data[0]['patronymic'] = $patronymic;
        $this->data[0]['educationYear'] =
            $this->data[0]['startYearA'].$this->data[0]['startYearB'].'/'.
            $this->data[0]['endYearA'].$this->data[0]['endYearB'];
    }

    /**
     * Подчёт суммы по переданному списку работ
     *
     * @param array $works
     * @param string $where
     * @return integer
     */
    private function calculateSum($works, $where = 'plan') {
        $sum = 0;

        foreach ($works as $work) {
            $sum += $work[$where];
        }

        return $sum;
    }

    /**
     * Подсчёт часов для каждого из разделов Excel документа
     *
     * @return void
     */
    private function calculateResult() {
        $workSum1 = $this->data[1]['sum'] + $this->data[2]['sum'];
        $workSum2 = $this->calculateSum($this->data[3]['work']);
        $workSum3 = $this->calculateSum($this->data[4]['work']);
        $workSum4 = $this->calculateSum($this->data[5]['work']);
        $sum = $workSum1 + $workSum2 + $workSum3 + $workSum4;

        $this->data[6] = [
            'workSum1' => $workSum1,
            'workSum2' => $workSum2,
            'workSum3' => $workSum3,
            'workSum4' => $workSum4,
            'sum' => $sum
        ];
    }


    /**
     * Возвращает лист с ранее полученными данными из Excel документа
     *
     * @param $sheet
     * @return mixed
     */
    public function getSheet($sheet) {
        if ($sheet < count($this->data)) {
            return $this->data[$sheet];
        }
    }

    /**
     * Возвращает массив полученных данных из Excel документа
     *
     * @return array
     */
    public function getData() {
        return $this->data;
    }

}
