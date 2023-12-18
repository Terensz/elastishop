                        <div class="table-responsive">
                            <table class="table table-hover m-b-0">
                                <thead>
                                    <tr>
                                        <th>Név</th>
                                        <th>E-mail</th>
                                        <th>Szerep</th>
                                        <th>Státusz</th>
                                        <th>Meghívó elküldve</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $scaleOwnerData['name']; ?></td>
                                        <td><?php echo $scaleOwnerData['email']; ?></td>
                                        <td>Tulajdonos</td>
                                        <td>Aktív</td>
                                        <td>-</td>
                                    </tr>
                                    <?php foreach ($projectTeamworkData as $projectTeamworkDataRow): ?>
                                    <tr>
                                        <td><?php echo $projectTeamworkDataRow['name']; ?></td>
                                        <td><?php echo $projectTeamworkDataRow['email']; ?></td>
                                        <td>Csapattag</td>
                                        <td>Aktív</td>
                                        <td>-</td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php foreach ($scaleTeamUnconfirmedInviteData as $scaleTeamUnconfirmedInviteDataRow): ?>
                                    <tr>
                                        <td><?php echo $scaleTeamUnconfirmedInviteDataRow['name']; ?></td>
                                        <td><?php echo $scaleTeamUnconfirmedInviteDataRow['email']; ?></td>
                                        <td>Meghívott</td>
                                        <td>Meghívó elküldve</td>
                                        <td><?php echo $scaleTeamUnconfirmedInviteDataRow['createdAt']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>