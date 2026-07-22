<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$stmt = $db->prepare(
    "SELECT date, title
     FROM todo
     WHERE student_id = ?
     AND completed = 0"
);

$stmt->execute([
    $_SESSION['student_id']
]);

$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$todoByDate = [];

foreach ($todos as $todo) {
    $todoByDate[$todo['date']][] = $todo['title'];
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カレンダー</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <h1>6月</h1>

    <table border="1">
        <tr>
            <th>日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th>土</th>
        </tr>

        <tr>
            <td></td>

            <td>
                <a href="date.php?date=2026-06-01">1</a>

                <?php if (isset($todoByDate['2026-06-01'])): ?>

                    <?php foreach ($todoByDate['2026-06-01'] as $title): ?>

                        <div>
                            <?= htmlspecialchars($title) ?>
                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </td>

            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
            <td>6</td>
        </tr>

        <tr>
            <td>7</td>
            <td>8</td>
            <td>9</td>
            <td>10</td>
            <td>11</td>
            <td>12</td>
            <td>13</td>
        </tr>

        <tr>
            <td>14</td>
            <td>15</td>
            <td>16</td>
            <td>17</td>
            <td>18</td>
            <td>19</td>
            <td>20</td>
        </tr>

        <tr>
            <td>21</td>
            <td>22</td>
            <td>23</td>
            <td>24</td>
            <td>25</td>
            <td>26</td>
            <td>27</td>
        </tr>

        <tr>
            <td>28</td>
            <td>29</td>
            <td>30</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

</body>
</html>