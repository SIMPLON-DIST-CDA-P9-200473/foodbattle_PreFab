<h1>Le top street food</h1>
<p>Les 10 plats qui ont reçu le plus de votes. À toi de faire bouger le classement !</p>

<table>
    <thead>
        <tr>
            <th>Rang</th>
            <th>Plat</th>
            <th>Établissement</th>
            <th>Votes</th>
            <th>Note /5</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $i => $d) : ?>
            <tr>
                <td>#<?= $i + 1 ?></td>
                <td><a href="/plat.php?id=<?= (int) $d['id'] ?>"><?= h($d['name']) ?></a></td>
                <td><?= h($d['restaurant']) ?></td>
                <td><?= (int) $d['votes'] ?></td>
                <td><?= number_format((float) $d['average'], 1, ',', '') ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
