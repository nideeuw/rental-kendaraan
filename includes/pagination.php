<?php

function paginate($page = 1, $total = 0, $perPage = 10)
{
    $page = max(1, (int)$page);
    $perPage = max(1, (int)$perPage);
    $totalPages = max(1, ceil($total / $perPage));
    $offset = ($page - 1) * $perPage;

    return [
        'current_page' => $page,
        'per_page' => $perPage,
        'total' => $total,
        'total_pages' => $totalPages,
        'offset' => $offset,
        'limit' => $perPage,
        'from' => $total > 0 ? $offset + 1 : 0,
        'to' => min($offset + $perPage, $total),
        'has_prev' => $page > 1,
        'has_next' => $page < $totalPages,
        'prev_page' => $page - 1,
        'next_page' => $page + 1
    ];
}

function showPagination($pagination, $url)
{
    $p = $pagination;

    $params = $_GET;
    $urlParts = parse_url($url);
    parse_str($urlParts['query'] ?? '', $params);

    // Build URL function
    function buildUrl($url, $params, $page, $perPage)
    {
        $urlParts = parse_url($url);
        $newParams = array_merge($params, [
            'page' => $page,
            'per_page' => $perPage
        ]);
        return $urlParts['path'] . '?' . http_build_query($newParams);
    }
?>
    <div class="pagination-box">
        <!-- Info -->
        <div class="pagination-info">
            Showing <?= number_format($p['from']); ?> - <?= number_format($p['to']); ?>
            of <?= number_format($p['total']); ?> entries
        </div>

        <!-- Controls -->
        <div class="pagination-controls">
            <!-- Previous -->
            <?php if ($p['has_prev']): ?>
                <a href="<?= $url; ?>&page=<?= $p['prev_page']; ?>&per_page=<?= $p['per_page']; ?>"
                    class="page-btn">Prev</a>
            <?php else: ?>
                <span class="page-btn disabled">Prev</span>
            <?php endif; ?>

            <!-- Page Numbers -->
            <div class="page-numbers">
                <?php
                $start = max(1, $p['current_page'] - 2);
                $end = min($p['total_pages'], $p['current_page'] + 2);

                // First page
                if ($start > 1) {
                    echo '<a href="' . $url . '&page=1&per_page=' . $p['per_page'] . '" class="page-num">1</a>';
                    if ($start > 2) echo '<span class="page-dots">...</span>';
                }

                // Middle pages
                for ($i = $start; $i <= $end; $i++) {
                    if ($i == $p['current_page']) {
                        echo '<span class="page-num active">' . $i . '</span>';
                    } else {
                        echo '<a href="' . $url . '&page=' . $i . '&per_page=' . $p['per_page'] . '" class="page-num">' . $i . '</a>';
                    }
                }

                // Last page
                if ($end < $p['total_pages']) {
                    if ($end < $p['total_pages'] - 1) echo '<span class="page-dots">...</span>';
                    echo '<a href="' . $url . '&page=' . $p['total_pages'] . '&per_page=' . $p['per_page'] . '" class="page-num">' . $p['total_pages'] . '</a>';
                }
                ?>
            </div>

            <!-- Next -->
            <?php if ($p['has_next']): ?>
                <a href="<?= $url; ?>&page=<?= $p['next_page']; ?>&per_page=<?= $p['per_page']; ?>"
                    class="page-btn">Next</a>
            <?php else: ?>
                <span class="page-btn disabled">Next</span>
            <?php endif; ?>

            <!-- Per Page -->
            <select class="per-page-select" onchange="window.location.href='<?= $url; ?>&page=1&per_page=' + this.value">
                <?php foreach ([10, 25, 50, 100, 500, 1000] as $size): ?>
                    <option value="<?= $size; ?>" <?= $p['per_page'] == $size ? 'selected' : ''; ?>>
                        <?= $size; ?> / page
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <script>
        function changePerPage(perPage, paramsJson) {
            try {
                var params = JSON.parse(paramsJson);
                params.page = 1; // Reset to page 1
                params.per_page = perPage;

                var queryString = Object.keys(params)
                    .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(params[key]))
                    .join('&');

                window.location.href = 'index.php?' + queryString;
            } catch (e) {
                console.error('Error changing per page:', e);
                // Fallback
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('page', 1);
                currentUrl.searchParams.set('per_page', perPage);
                window.location.href = currentUrl.toString();
            }
        }
    </script>
<?php
}
?>