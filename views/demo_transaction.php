<?php
$page_title = "Manual Rollback Demo";
include __DIR__ . '/../includes/header.php';

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/RentalModel.php';

$database = new Database();
$db = $database->getConnection();
$rentalModel = new RentalModel($db);

$testResult = null;

// Handle test actions
if (isset($_POST['demo_action'])) {
    $data = [
        'id_kendaraan' => $_POST['id_kendaraan'],
        'id_sopir' => $_POST['id_sopir']
    ];

    switch ($_POST['demo_action']) {
        case 'force_error':
            $testResult = $rentalModel->demoRollbackForceError($data);
            break;
        
        case 'savepoint':
            $testResult = $rentalModel->demoRollbackSavepoint($data);
            break;
        
        case 'manual_commit':
            $testResult = $rentalModel->demoRollbackManual($data, false); // shouldRollback = false
            break;
        
        case 'manual_rollback':
            $testResult = $rentalModel->demoRollbackManual($data, true); // shouldRollback = true
            break;
        
        case 'with_proof':
            $testResult = $rentalModel->demoRollbackWithProof($data);
            break;
    }
}
?>

<div class="content-box">
    <div class="content-header">
        <h2>Manual Rollback Demo</h2>
        <p style="color: #666;">Demonstrasi rollback untuk presentasi ke dosen</p>
    </div>

    <!-- Test Result -->
    <?php if ($testResult !== null): ?>
        <div class="alert alert-<?= $testResult['success'] ? 'success' : 'warning'; ?>" style="margin-bottom: 30px;">
            <h3><?= $testResult['action'] ?? 'Result'; ?></h3>
            <p><strong>Message:</strong> <?= htmlspecialchars($testResult['message']); ?></p>
            
            <?php if (isset($testResult['proof'])): ?>
                <div style="background: white; padding: 15px; margin-top: 15px; border-radius: 4px;">
                    <h4>Proof of Rollback:</h4>
                    <table style="width: 100%; margin-top: 10px;">
                        <tr>
                            <td><strong>Before:</strong></td>
                            <td><code><?= $testResult['proof']['before']; ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>During Transaction:</strong></td>
                            <td><code><?= $testResult['proof']['during']; ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>After Rollback:</strong></td>
                            <td><code><?= $testResult['proof']['after']; ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Rollback Works?</strong></td>
                            <td>
                                <strong style="color: <?= $testResult['proof']['rollback_works'] ? 'green' : 'red'; ?>">
                                    <?= $testResult['proof']['rollback_works'] ? 'YES' : 'NO'; ?>
                                </strong>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($testResult['log'])): ?>
                <details style="margin-top: 20px;">
                    <summary style="cursor: pointer; font-weight: bold;">Transaction Log (Click to expand)</summary>
                    <div style="background: #1e1e1e; color: #fff; padding: 20px; margin-top: 10px; border-radius: 4px; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.8;">
                        <?php foreach ($testResult['log'] as $log): ?>
                            <div><?= htmlspecialchars($log); ?></div>
                        <?php endforeach; ?>
                    </div>
                </details>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Demo Scenarios -->
    <div class="scenarios-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
        
        <!-- Scenario 1: Force Error -->
        <div class="scenario-card" style="border: 2px solid #f44336; border-radius: 8px; padding: 20px; background: #fef5f5;">
            <h3 style="color: #f44336;">Demo 1: Force Error</h3>
            <p>Update kendaraan & sopir, lalu trigger error → ROLLBACK</p>
            
            <form method="POST" style="margin-top: 20px;">
                <input type="hidden" name="demo_action" value="force_error">
                
                <label>Kendaraan ID:</label>
                <input type="number" name="id_kendaraan" class="form-input" value="1" required>
                
                <label>Sopir ID:</label>
                <input type="number" name="id_sopir" class="form-input" value="1" required>
                
                <button type="submit" class="btn btn-danger" style="width: 100%; margin-top: 15px;">
                    Test Force Error Rollback
                </button>
            </form>
        </div>

        <!-- Scenario 2: Savepoint -->
        <div class="scenario-card" style="border: 2px solid #ff9800; border-radius: 8px; padding: 20px; background: #fff8f3;">
            <h3 style="color: #ff9800;">Demo 2: Savepoint</h3>
            <p>Demo partial rollback dengan SAVEPOINT</p>
            
            <form method="POST" style="margin-top: 20px;">
                <input type="hidden" name="demo_action" value="savepoint">
                
                <label>Kendaraan ID:</label>
                <input type="number" name="id_kendaraan" class="form-input" value="1" required>
                
                <label>Sopir ID:</label>
                <input type="number" name="id_sopir" class="form-input" value="1" required>
                
                <button type="submit" class="btn" style="background: #ff9800; color: white; width: 100%; margin-top: 15px;">
                    Test Savepoint Rollback
                </button>
            </form>
        </div>

        <!-- Scenario 3: Manual Rollback Button -->
        <div class="scenario-card" style="border: 2px solid #9c27b0; border-radius: 8px; padding: 20px; background: #faf5fc;">
            <h3 style="color: #9c27b0;">Demo 3: Manual Choice</h3>
            <p>Pilih sendiri: COMMIT atau ROLLBACK</p>
            
            <form method="POST" style="margin-top: 20px;">
                <label>Kendaraan ID:</label>
                <input type="number" name="id_kendaraan" class="form-input" value="1" required>
                
                <label>Sopir ID:</label>
                <input type="number" name="id_sopir" class="form-input" value="1" required>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px;">
                    <button type="submit" name="demo_action" value="manual_commit" class="btn btn-success">
                        COMMIT
                    </button>
                    <button type="submit" name="demo_action" value="manual_rollback" class="btn btn-danger">
                        ROLLBACK
                    </button>
                </div>
            </form>
        </div>

        <!-- Scenario 4: With Proof -->
        <div class="scenario-card" style="border: 2px solid #2196f3; border-radius: 8px; padding: 20px; background: #e3f2fd;">
            <h3 style="color: #2196f3;">Demo 4: With Proof</h3>
            <p>BUKTIKAN rollback works dengan compare before/after</p>
            
            <form method="POST" style="margin-top: 20px;">
                <input type="hidden" name="demo_action" value="with_proof">
                
                <label>Kendaraan ID:</label>
                <input type="number" name="id_kendaraan" class="form-input" value="1" required>
                
                <label>Sopir ID:</label>
                <input type="number" name="id_sopir" class="form-input" value="1" required>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 15px;">
                    Test with Proof
                </button>
            </form>
        </div>
    </div>

    <!-- Explanation -->
    <div class="info-box" style="background: #f5f5f5; padding: 25px; border-radius: 8px; margin-top: 40px;">
        <h3>Penjelasan Manual Rollback</h3>

        <h4 style="margin-top: 20px;">4 Method Demo:</h4>
        <table class="data-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th>Method</th>
                    <th>Cara Kerja</th>
                    <th>Kegunaan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Force Error</strong></td>
                    <td>Sengaja throw exception</td>
                    <td>Demo rollback otomatis saat error</td>
                </tr>
                <tr>
                    <td><strong>Savepoint</strong></td>
                    <td>Rollback ke checkpoint</td>
                    <td>Demo partial rollback</td>
                </tr>
                <tr>
                    <td><strong>Manual Choice</strong></td>
                    <td>Button COMMIT/ROLLBACK</td>
                    <td>User decide: commit or rollback</td>
                </tr>
                <tr>
                    <td><strong>With Proof</strong></td>
                    <td>Compare before/after</td>
                    <td>BUKTIKAN rollback works</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px; text-align: center;">
        <a href="index.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>