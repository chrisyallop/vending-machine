<?php
// Routes

$app->get('/[{name}]', function (\Slim\Http\Request $request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    $sellingPrice   = \App\Domain\Model\Money::fromAmount(50);
    $vendingMachine = \App\Domain\Model\VendingMachine::startWithSellingPrice($sellingPrice);

    $purchaseAmount = $request->getParam('purchaseAmount');
    if ($purchaseAmount) {
        $purchaseAmount = \App\Domain\Model\Money::fromCoin((int) $purchaseAmount);
        $change         = $vendingMachine->purchaseItem($purchaseAmount);
        $args['change'] = $change;
    }

    $args['vendingMachine'] = $vendingMachine;

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
