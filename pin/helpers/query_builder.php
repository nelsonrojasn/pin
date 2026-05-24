<?php
/**
 * Procedural QueryBuilder helpers
 * Small helper functions to build simple SELECT queries as an alternative
 * to the old `QueryBuilder` class.
 */

function qb(string $table): array
{
    return [
        'table' => $table,
        'columns' => ['*'],
        'conditions' => [],
        'joins' => [],
        'group' => null,
        'having' => null,
        'orderBy' => [],
        'limit' => null,
    ];
}

function qb_columns(array $qb, string $columns): array
{
    $parts = array_map('trim', explode(',', $columns));
    $qb['columns'] = $parts === [''] ? ['*'] : $parts;
    return $qb;
}

function qb_where(array $qb, string $condition): array
{
    if (empty($qb['conditions'])) {
        $qb['conditions'][] = ['operator' => null, 'condition' => $condition];
    } else {
        $qb['conditions'][] = ['operator' => 'AND', 'condition' => $condition];
    }

    return $qb;
}

function qb_or_where(array $qb, string $condition): array
{
    if (empty($qb['conditions'])) {
        $qb['conditions'][] = ['operator' => null, 'condition' => $condition];
    } else {
        $qb['conditions'][] = ['operator' => 'OR', 'condition' => $condition];
    }

    return $qb;
}

function qb_join(array $qb, string $join): array
{
    $qb['joins'][] = $join;
    return $qb;
}

function qb_limit(array $qb, int $limit): array
{
    $qb['limit'] = 'LIMIT ' . $limit;
    return $qb;
}

function qb_group(array $qb, string $group): array
{
    $qb['group'] = 'GROUP BY ' . $group;
    return $qb;
}

function qb_having(array $qb, string $having): array
{
    $qb['having'] = 'HAVING ' . $having;
    return $qb;
}

function qb_order_by(array $qb, string $orderBy): array
{
    $parts = array_map('trim', explode(',', $orderBy));
    $qb['orderBy'] = array_merge($qb['orderBy'], $parts);
    return $qb;
}

function qb_to_sql(array $qb): string
{
    $parts = [];
    $parts[] = 'SELECT ' . implode(', ', $qb['columns']) . ' FROM ' . $qb['table'];

    if (!empty($qb['conditions'])) {
        $where = '';
        foreach ($qb['conditions'] as $idx => $c) {
            if ($idx === 0) {
                $where .= $c['condition'];
            } else {
                $where .= ' ' . $c['operator'] . ' ' . $c['condition'];
            }
        }
        $parts[] = 'WHERE ' . $where;
    }

    if (!empty($qb['joins'])) {
        foreach ($qb['joins'] as $j) {
            $parts[] = trim($j);
        }
    }

    if ($qb['group'] !== null) {
        $parts[] = $qb['group'];
    }

    if ($qb['having'] !== null) {
        $parts[] = $qb['having'];
    }

    if (!empty($qb['orderBy'])) {
        $parts[] = 'ORDER BY ' . implode(', ', $qb['orderBy']);
    }

    if ($qb['limit'] !== null) {
        $parts[] = $qb['limit'];
    }

    return implode(' ', $parts);
}

function qb_select(string $table, array $opts = []): string {
  $qb = qb($table);
  if (isset($opts['columns'])) $qb = qb_columns($qb, $opts['columns']);
  if (!empty($opts['joins'])) foreach ((array)$opts['joins'] as $j) $qb = qb_join($qb, $j);
  if (isset($opts['where'])) $qb = qb_where($qb, $opts['where']);
  if (isset($opts['or_where'])) $qb = qb_or_where($qb, $opts['or_where']);
  if (isset($opts['order'])) $qb = qb_order_by($qb, $opts['order']);
  if (isset($opts['limit'])) $qb = qb_limit($qb, (int)$opts['limit']);
  if (isset($opts['group'])) $qb = qb_group($qb, $opts['group']);
  if (isset($opts['having'])) $qb = qb_having($qb, $opts['having']);
  return qb_to_sql($qb);
}

function qb_str(array $qb): string
{
    return qb_to_sql($qb);
}
