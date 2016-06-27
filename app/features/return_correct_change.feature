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
      |      50      |       100      |      50      |          1           |      50      |
      |      80      |       100      |      20      |          1           |      20      |
      |      90      |       100      |      10      |          1           |      10      |
      |      95      |       100      |      05      |          1           |      05      |
      |      98      |       100      |      02      |          1           |      02      |
      |      99      |       100      |      01      |          1           |      01      |
