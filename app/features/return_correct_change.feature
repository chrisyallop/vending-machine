Feature: Return correct change
  In order to not pay more than an item is worth or to avoid skipping the purchase completely
  As a customer
  I want to purchase an item and receive the correct change

  Scenario: No change, exact amount given
    Given a vending machine, dispensing items priced at "100"p
    When I purchase an item for "100"p
    Then I should receive change of "0"p

  Scenario Outline: Single coin returned
    Given a vending machine, dispensing items priced at "<sellingPrice>"p
    When I purchase an item for "<purchaseAmount>"p
    Then I should receive change of "<changeAmount>"p
    And in the denominations of one "<denomination>"p

    Examples:
      | sellingPrice | purchaseAmount | changeAmount | denomination |
      |      50      |       100      |      50      |      50      |
      |      80      |       100      |      20      |      20      |
      |      90      |       100      |      10      |      10      |
      |      95      |       100      |       5      |       5      |
      |      98      |       100      |       2      |       2      |
      |      99      |       100      |       1      |       1      |

  Scenario Outline: Two coins returned
    Given a vending machine, dispensing items priced at "<sellingPrice>"p
    When I purchase an item for "<purchaseAmount>"p
    Then I should receive change of "<changeAmount>"p
    And in the denominations of one "<denomination_1>"p and one "<denomination_2>"p

    Examples:
      | sellingPrice | purchaseAmount | changeAmount | denomination_1 | denomination_2 |
      |      50      |       200      |     150      |     100        |      50        |
      |      80      |       200      |     120      |     100        |      20        |
      |      90      |       200      |     110      |     100        |      10        |
      |      95      |       200      |     105      |     100        |       5        |
      |      98      |       200      |     102      |     100        |       2        |
      |      99      |       200      |     101      |     100        |       1        |
      |      30      |       100      |      70      |      50        |      20        |
      |      40      |       100      |      60      |      50        |      10        |
      |      45      |       100      |      55      |      50        |       5        |
      |      48      |       100      |      52      |      50        |       2        |
      |      49      |       100      |      51      |      50        |       1        |
      |      60      |       100      |      40      |      20        |      20        |
      |      70      |       100      |      30      |      20        |      10        |
      |      75      |       100      |      25      |      20        |       5        |
      |      78      |       100      |      22      |      20        |       2        |
      |      79      |       100      |      21      |      20        |       1        |
      |      85      |       100      |      15      |      10        |       5        |
      |      88      |       100      |      12      |      10        |       2        |
      |      89      |       100      |      11      |      10        |       1        |
      |      93      |       100      |       7      |       5        |       2        |
      |      94      |       100      |       6      |       5        |       1        |
      |      96      |       100      |       4      |       2        |       2        |
      |      97      |       100      |       3      |       2        |       1        |

  Scenario Outline: Three coins returned
    Given a vending machine, dispensing items priced at "<sellingPrice>"p
    When I purchase an item for "<purchaseAmount>"p
    Then I should receive change of "<changeAmount>"p
    And in the denominations of one "<denomination_1>"p, one "<denomination_2>"p and one "<denomination_3>"p

    Examples:
      | sellingPrice | purchaseAmount | changeAmount | denomination_1 | denomination_2 | denomination_3 |
      |      30      |       200      |     170      |     100        |      50        |      20        |
      |      20      |       100      |      80      |      50        |      20        |      10        |
      |      15      |        50      |      35      |      20        |      10        |       5        |
      |      18      |        50      |      32      |      20        |      10        |       2        |
      |      19      |        50      |      31      |      20        |      10        |       1        |
      |      23      |        50      |      27      |      20        |       5        |       2        |
      |      24      |        50      |      26      |      20        |       5        |       1        |
      |       3      |        20      |      17      |      10        |       5        |       2        |
      |       4      |        20      |      16      |      10        |       5        |       1        |
      |       7      |        20      |      13      |      10        |       2        |       1        |
      |       1      |        10      |       9      |       5        |       2        |       2        |
      |       2      |        10      |       8      |       5        |       2        |       1        |
