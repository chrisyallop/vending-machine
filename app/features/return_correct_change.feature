Feature: Return correct change
  In order to not pay more than an item is worth or to avoid skipping the purchase completely
  As a customer
  I want to purchase an item and receive the correct change

  Scenario: No change, exact amount given
    Given a vending machine, dispensing items priced at "100"p
    When I purchase an item for "100"p
    Then I should receive change to the amount of "0"p

  Scenario Outline: Single coin returned
    Given a vending machine, dispensing items priced at "<sellingPrice>"p
    When I purchase an item for "<purchaseAmount>"p
    Then I should receive change to the amount of "<changeAmount>"p in "<denominationQuantity>" "<denomination>"p coins

    Examples:
      | sellingPrice | purchaseAmount | changeAmount | denominationQuantity | denomination |
      |     100      |       50       |      50      |          1           |      50      |
      |     100      |       80       |      20      |          1           |      20      |
      |     100      |       90       |      10      |          1           |      10      |
      |     100      |       95       |      05      |          1           |      05      |
      |     100      |       98       |      02      |          1           |      02      |
      |     100      |       99       |      01      |          1           |      01      |
