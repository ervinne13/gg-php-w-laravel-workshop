# Laravel Introduction

We'll try to make sense of Laravel's design over its features. If you want a feature list, go to the [documentation](https://laravel.com/docs/5.8) instead as we'll not repeat what's already there here.

## Apparent Design

Laravel's design seems to be a mix of considering "purity" and "simplicity" with the framework leaning more towards "simplicity" than "purity".

It boasts of expressive and beautiful sytax.

## Pure vs Simple Design

### Pure Design
Pure (coming from "purists") code in this context is code that very strictly follows standards and design patterns.

__Pure code has the following advantages:__
- Very Extensible
- Normally results in Robust libraries/projects
- "Lego" blocks of code where reuse is very easy (sometimes arguable) as long as you know what you are doing
    - In fact, pure code can be reused without framework, or in any framework.
- "Religously" following design pattern standards
- SOLID

__But has the following disadvantages:__
- For small projects, adding features may be cumbersome
    - Because you'll have to code a LOT before getting anything done
    - Developers must be very familiar with the established standards
- Learning curve is usually steep

__There are gray areas too:__
- Future proofing (this can be good or bad depending on the context)

__Examples outside Laravel:__
- JavaScript's Redux library/design.
- Zend Framework
- Java 2 Enterprise Edition specs
- Symfony's Doctrine (or any data mapper)

### Simple Design

Simple design in this context is really just making things work and readable at the same time.

__Advantages usually include:__
- Easier to use, less code, do more
- Arguably easier to read
- YAGNI

__There are, drawbacks of course:__
- Too pragmatic/simple implementations often times impare reusability outside it's intended use (ie, outside Laravel).
    - If there's reuse, it's usually limited inside where the code is written.
- Usually makes use of bad design, code with code smell. Several good examples of this is:
    - Laravel's Facade (not to be confused with Facade design pattern)
    - Eloquent (More on this later when we discuss, OOP Fail: Doing functional programming instead of OOP in OOP)

Examples outside Laravel:
- Symfony (except Doctrine)
- Django (Python Framwork)

### In Summary

Pure design is strictly following standards while simple design focuses on simplicity and ease of use. Simplicity is pragmatism and purity is establishing order. Simplicity is YAGNI, purity is SOLID.

"Make user-happiness your top priority, not adherence to a design pattern" - Jeffrey Way, Things Laravel Made Me Believe (2015)
- User in this context is you, developers, users of Laravel.
- Signifies priority of simplicity over purity.

## Instructor Insight

I'm going to share my personal insight, this is not a lesson, but merely sharing thoughts.

Background about my experience first:
- Came from mostly strictly typed programming languages (C/C++/C#/Java) primarily coding in Java.
- Enjoyed strict (in effect pure) programming.
- Design patterns enthusiast.

Now that that's out of the way, I mostly agree with how Laravel does things. It's a mix of both worlds, both simple and pure, both YAGNI and SOLID.

In fact, Facades and Eloquent is completely optional (though lots of features may be lost if you don't use Eloquent). I did a couple of projects with Laravel + Doctrine before and would attest that it's a bit more difficult, but the objectives of the project being pure and model behavior and use cases instead of databases was achieved.

Jeffrey Way can give even more insight in his talk [Things Laravel Made Me Believe](https://www.youtube.com/watch?v=mDotS5BDqRM).
- Jump at 19:51 to 21:20 as he discuss "One true way.. is BS"
- Jump at 37:40 to 38:40 on "Context"
- Jump at 42:34 to 45:40 on violations on design patterns/SOLID principles and repository patterns.
- But watch it all please :)

3 years before, I would probably disagree (a lot) with Jeffrey Way especially as I grew up idolizing Martin Fowler and Robert Martin. But now, with the existence of Test Driven Development, I'd rather follow Jeffrey's "Simple by default" design, then "IF" I really need some purity in the code, I refactor, `that's what the tests are for, so that you can refactor`.