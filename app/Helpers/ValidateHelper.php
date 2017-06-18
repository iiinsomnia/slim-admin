<?php
namespace App\Helpers;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

/**
* 验证辅助类
* 基于：respect/validation
* 文档：http://respect.github.io/Validation/
*/
class ValidateHelper
{
    static $msg = [
        'notOptional' => '{{name}}不可为空',
        'intVal'      => '{{name}}必须为整数',
        'numeric'     => '{{name}}必须为数字',
        'email'       => '{{name}}不是合法的邮箱格式',
        'length'      => '{{name}}长度最大为{{maxValue}}',
        'between'     => '{{name}}须在{{minValue}}和{{maxValue}}之间',
        'date'        => '{{name}}不是合法的日期格式：{{format}}',
        'equals'      => '{{name}}必须和{{compareTo}}相同',
        'contains'    => '{{name}}必须包含{{containsValue}}',
        'in'          => '{{name}}只能在{{haystack}}之中',
        'regex'       => '{{name}}不符合输入规则：{{regex}}',
    ];

    /**
     * 验证数据的合法性
     * @param array $data 验证的数据
     * @param array $rules 验证规则
     *        [
     *           'id' => [
     *               'label' => 'ID',
     *               'valids' => [v::intVal()],
     *               'required' => true,
     *           ],
     *           'name' => [
     *               'label' => '名称',
     *               'valids' => [v::length(null, 20)],
     *               'required' => true,
     *           ],
     *           'email' => [
     *               'label' => '邮箱',
     *               'valids' => [v::email()],
     *               'required' => false,
     *           ],
     *       ]
     * @return array 返回错误信息
     */
    public static function validate($data, $rules)
    {
        $errors = [];

        foreach ($rules as $k => $r) {
            try {
                $input = isset($data[$k]) ? $data[$k] : '';

                if (isset($r['required']) && $r['required']) {
                    v::notOptional()->setName($r['label'])->assert($input);
                } else {
                    if (!v::notOptional()->validate($input)) {
                        continue;
                    }
                }

                $valids = !empty($r['valids']) ? $r['valids'] : [];

                if (!empty($valids)) {
                    foreach ($valids as $v) {
                        $v->setName($r['label'])->assert($input);
                    }
                }
            } catch (NestedValidationException $e) {
                $errors[] = $e->findMessages(self::$msg);
            }
        }

        $errors = self::formatErrors($errors);

        return $errors;
    }

    // 格式化错误信息
    protected static function formatErrors($errors)
    {
        $result = [];

        foreach ($errors as $err) {
            foreach ($err as $v) {
                if (!empty($v)) {
                    $result[] = $v;
                }
            }
        }

        return $result;
    }
}
?>