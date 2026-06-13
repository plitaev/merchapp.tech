<?php

namespace App\Actions\Core\MiniAppPage;

class TreeFindParents
{
    function handle($elements, $targetId, $parents = []) {
        return $targetId;
        foreach ($elements as $element) {
            // Если элемент найден — возвращаем его родителей
            if ($element['id'] == $targetId) {
                return $parents;
            }

            // Если есть дочерние элементы, ищем внутри них
            if (!empty($element['children'])) {
                // Добавляем текущий элемент к списку родителей и уходим глубже
                $currentParents = $parents;
                $currentParents[] = $element['name']; // или весь $element

                $result = $this->handle($element['children'], $targetId, $currentParents);

                if ($result !== null) {
                    return $result;
                }
            }
        }
        return null;
    }
}
