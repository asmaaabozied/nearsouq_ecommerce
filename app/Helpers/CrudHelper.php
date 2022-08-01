<?php


namespace App\Helpers;


class CrudHelper
{
    public static function getBooleanBadge($id, $table, $field) {
        $item = \DB::table($table)->where('id', $id)->first();
        if (!isset($item->{$field})) return false;

        if (isset($item->{$field}) && $item->{$field} == 1) {
            $html = '<span class="label label-success">YES</span>';
        } else {
            $html = '<span class="label label-danger">NO</span>';
        }
        return $html;
    }
    public static function getIconBadge($id, $table, $field) {
        $item = \DB::table($table)->where('id', $id)->first();
        if (!isset($item->{$field})) return false;

        $html = "";
        if (isset($item->{$field})) {
            $html = '<a class="btn btn-default"><i class="fa '.$item->{$field}.'"></i></a>';
        }
        return $html;
    }
}
