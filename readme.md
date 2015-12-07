# Boggle Solver challenge

[Boggle](http://en.wikipedia.org/wiki/Boggle) is a word game played with
alphabetic letter tiles arranged randomly on a grid. Players must identify
words spelled by sequential adjacent letters in any direction
(horizontally, vertically or diagonally), but may not use the same
letter tile twice.

## Challenge

Write a program that solves a 4x4 randomly arranged Boggle grid.

## Going Further

* What external resources would help?
* What factors impact performance? What would you do to improve them?
* What are the key data structures in use here? What makes them more
  appropriate than alternatives?
* Can your solution handle words that occur within other words? i.e.
  'catcher' -> ['cat', 'catch', 'her']

## Solutions

Wrote a program using laravel that makes a board by getting the squares and adjacent squares based on the board size.
From there, generate a letter and return the board as an ajax call in front ned.

I used vuejs because i never have before and felt like it would benefit my knowledge base to become familiar with it.

Performance is impacted by the balance between front end and back end. You could make the board on the front end, or even generate it on the backend. but you lose some UX out of the whole thing.



## Forward Thinking

This is not the most efficient way. Looking at it from the game itself to begin with and then fitting a solver into it was a bad idea but i was determined to do it.
Next time i would just make a work based application