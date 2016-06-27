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
      |      50      |       100      |      50      |         50         |
      |      80      |       100      |      20      |         20         |
      |      90      |       100      |      10      |         10         |
      |      95      |       100      |       5      |          5         |
      |      98      |       100      |       2      |          2         |
      |      99      |       100      |       1      |          1         |

  Scenario Outline: Two coins returned
    Given a vending machine, dispensing items priced at "<sellingPrice>"p
    When I purchase an item for "<purchaseAmount>"p
    Then I should receive change to the amount of "<changeAmount>"p with "1" denomination at "<denomination_1>"p and "1" denomination at "<denomination_2>"p

    Examples:
      | sellingPrice | purchaseAmount | changeAmount | denomination_1 | denomination_2 |
      |      30      |       100      |      70      |      50        |      20        |
      |      40      |       100      |      60      |      50        |      10        |
      |      45      |       100      |      55      |      50        |       5        |
      |      48      |       100      |      52      |      50        |       2        |
      |      49      |       100      |      51      |      50        |       1        |
      |      70      |       100      |      30      |      20        |      10        |
      |      75      |       100      |      25      |      20        |       5        |
      |      78      |       100      |      22      |      20        |       2        |
      |      79      |       100      |      21      |      20        |       1        |
      |      85      |       100      |      15      |      10        |       5        |
      |      88      |       100      |      12      |      10        |       2        |
      |      89      |       100      |      11      |      10        |       1        |
      |      93      |       100      |      07      |       5        |       2        |
      |      94      |       100      |      06      |       5        |       1        |
      |      97      |       100      |      03      |       2        |       1        |
