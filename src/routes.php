<?php
// Routes

$app->get('/[{name}]', function (\Slim\Http\Request $request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    $sellingPrice   = \App\Domain\Model\Money::fromAmount(50);
    $vendingMachine = \App\Domain\Model\VendingMachine::startWithSellingPrice($sellingPrice);
    $vendingMachine->setInventory([
        100  =>  11,
         50  =>  24,
         20  =>   0,
         10  =>  99,
          5  => 200,
          2  =>  11,
          1  =>  23,
    ]);

    $args['sellingPrice']       = $vendingMachine->getSellingPrice()->getAmount();
    $args['startingInventory']  = $vendingMachine->getInventory();

    $purchaseAmount = $request->getParam('purchaseAmount');
    if ($purchaseAmount) {
        $purchaseAmount = \App\Domain\Model\Money::fromAmount((int) $purchaseAmount);
        $change         = $vendingMachine->purchaseItem($purchaseAmount);

        $args['purchaseAmount']     = $purchaseAmount->getAmount();
        $args['change']             = $change;
        $args['closingInventory']   = $vendingMachine->getInventory();
    }

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
