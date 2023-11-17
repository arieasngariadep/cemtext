<table>
    <thead>
        <tr>
            <th colspan="9" align="center"><b>KLAIM DISPUTE QRIS <?= $tanggalReport ?></b></th>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <th>No.</th>
            <th>Kode (RC)</th>
            <th>Jenis Transaksi</th>
            <th>Fee Dispute Net Amount (HAK)</th>
            <th>Dispute Net Amount (HAK)</th>
            <th>Jumlah (HAK)</th>
            <th>Fee Dispute Net Amount (KEWAJIBAN)</th>
            <th>Dispute Net Amount (KEWAJIBAN)</th>
            <th>Jumlah (KEWAJIBAN)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>30</td>
            <td>Charge Back</td>
            <td><?= $kode30hak->fee_return ?></td>
            <td><?= $kode30hak->dispute_net_amount ?></td>
            <td><?= $kode30hak->total_item ?></td>
            <td><?= $kode30kewajiban->fee_return ?></td>
            <td><?= $kode30kewajiban->dispute_net_amount ?></td>
            <td><?= $kode30kewajiban->total_item ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td>31</td>
            <td>Charge Back Reversal</td>
            <td><?= $kode31hak->fee_return ?></td>
            <td><?= $kode31hak->dispute_net_amount ?></td>
            <td><?= $kode31hak->total_item ?></td>
            <td><?= $kode31kewajiban->fee_return ?></td>
            <td><?= $kode31kewajiban->dispute_net_amount ?></td>
            <td><?= $kode31kewajiban->total_item ?></td>
        </tr>
        <tr>
            <td>3</td>
            <td>40</td>
            <td>Re-Presentment </td>
            <td><?= $kode40hak->fee_return ?></td>
            <td><?= $kode40hak->dispute_net_amount ?></td>
            <td><?= $kode40hak->total_item ?></td>
            <td><?= $kode40kewajiban->fee_return ?></td>
            <td><?= $kode40kewajiban->dispute_net_amount ?></td>
            <td><?= $kode40kewajiban->total_item ?></td>
        </tr>
        <tr>
            <td>4</td>
            <td>41</td>
            <td>Re-Presentment Reversal </td>
            <td><?= $kode41hak->fee_return ?></td>
            <td><?= $kode41hak->dispute_net_amount ?></td>
            <td><?= $kode41hak->total_item ?></td>
            <td><?= $kode41kewajiban->fee_return ?></td>
            <td><?= $kode41kewajiban->dispute_net_amount ?></td>
            <td><?= $kode41kewajiban->total_item ?></td>
        </tr>
        <tr>
            <td>5</td>
            <td>81</td>
            <td>Goodfaith Result </td>
            <td><?= $kode81hak->fee_return ?></td>
            <td><?= $kode81hak->dispute_net_amount ?></td>
            <td><?= $kode81hak->total_item ?></td>
            <td><?= $kode81kewajiban->fee_return ?></td>
            <td><?= $kode81kewajiban->dispute_net_amount ?></td>
            <td><?= $kode81kewajiban->total_item ?></td>
        </tr>
        <tr>
            <td>6</td>
            <td>82</td>
            <td>Goodfaith Penalty </td>
            <td><?= $kode82hak->fee_return ?></td>
            <td><?= $kode82hak->dispute_net_amount ?></td>
            <td><?= $kode82hak->total_item ?></td>
            <td><?= $kode82kewajiban->fee_return ?></td>
            <td><?= $kode82kewajiban->dispute_net_amount ?></td>
            <td><?= $kode82kewajiban->total_item ?></td>
        </tr>
        <tr>
            <td>7</td>
            <td>91</td>
            <td>Adjustment Credit Issuer (Koreksi) </td>
            <td><?= $kode91hak->fee_return ?></td>
            <td><?= $kode91hak->dispute_net_amount ?></td>
            <td><?= $kode91hak->total_item ?></td>
            <td><?= $kode91kewajiban->fee_return ?></td>
            <td><?= $kode91kewajiban->dispute_net_amount ?></td>
            <td><?= $kode91kewajiban->total_item ?></td>
        </tr>
        <tr>
            <td>8</td>
            <td>92</td>
            <td>Adjustment Credit Acquirer (Koreksi) </td>
            <td><?= $kode92hak->fee_return ?></td>
            <td><?= $kode92hak->dispute_net_amount ?></td>
            <td><?= $kode92hak->total_item ?></td>
            <td><?= $kode92kewajiban->fee_return ?></td>
            <td><?= $kode92kewajiban->dispute_net_amount ?></td>
            <td><?= $kode92kewajiban->total_item ?></td>
        </tr>
        <tr>
            <td>9</td>
            <td>93</td>
            <td>Pro Active Issuer </td>
            <td><?= $kode93hak->fee_return ?></td>
            <td><?= $kode93hak->dispute_net_amount ?></td>
            <td><?= $kode93hak->total_item ?></td>
            <td><?= $kode93kewajiban->fee_return ?></td>
            <td><?= $kode93kewajiban->dispute_net_amount ?></td>
            <td><?= $kode93kewajiban->total_item ?></td>
        </tr>
        <tr>
            <td>10</td>
            <td>94</td>
            <td>Pro Active Acquirer </td>
            <td><?= $kode94hak->fee_return ?></td>
            <td><?= $kode94hak->dispute_net_amount ?></td>
            <td><?= $kode94hak->total_item ?></td>
            <td><?= $kode94kewajiban->fee_return ?></td>
            <td><?= $kode94kewajiban->dispute_net_amount ?></td>
            <td><?= $kode94kewajiban->total_item ?></td>
        </tr>
    </tbody>
</table>