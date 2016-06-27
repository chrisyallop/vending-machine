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
    Then I should receive change to the amount of "<changeAmount>"p in "1" denomination of "<denominationAmount>"p coins

    Examples:
      | sellingPrice | purchaseAmount | changeAmount | denominationAmount |
      |      50      |       100      |      50      |      50            |
      |      80      |       100      |      20      |      20            |
      |      90      |       100      |      10      |      10            |
      |      95      |       100      |      05      |      05            |
      |      98      |       100      |      02      |      02            |
      |      99      |       100      |      01      |      01            |
