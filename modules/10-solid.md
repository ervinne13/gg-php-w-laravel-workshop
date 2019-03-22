# SOLID Principles

SOLID principles is a set of principles/recommendations on how to create better software.
Note that the instructor wants you to see SOLID as a very good foundation BUT, you should never overuse it like majority of developers do nowadays. They are `recommendations` not absolute rules.

The principles are mental cubby-holes. They give a name to a concept so that you can talk and reason about that concept. They provide a place to hang the feelings we have about good and bad code. They attempt to categorize those feelings into concrete advice.

My last tip would be, always follow and consider KISS (keep it simple stupid) and YAGNI (You aren't gonna need it) first before doing SOLID.

In the next parts of this workshop, we'll look at sample source codes on how to implement SOLID and in some cases, how a certain principle is abused (mostly SRP) and explore alternatives to attempt to make it better.

I'll reiterate the formula:

```
(YAGNI + KISS) > SOLID
```

<hr>

## The Principles Explained

We'll concentrate a lot on __SRP__, __OCP__, and __DIP__ there's a lot of controversy in __SRP__ and __OCP__ and look at ways how laravel implements and down right ingores the principles for simplicity. We'll discuss __LSP__ very shortly but not so much as we don't need to dig in deep to this subject to unwrap Laravel. The same applies with __ISP__ but we'll touch this more as it will introduce a very important concept called __Law of Demeter__ that will really help you right cleaner code.

Dependency inversion however, is probably the most important as far as we're concerned as Laravel's core revolves around dependency inversion/injection by means of its customized container.

- S [Single Responsibility Principle](/modules/10-solid/srp.md)
- O [Open/Closed Principle](/modules/10-solid/ocp.md)

- (We'll cut short for now) [Coupling](/modules/10-solid/coupling.md)

- D [Dependency Inversion Principle](/modules/10-solid/dip.md)
- I [Interface Segragation Principle](/modules/10-solid/isp.md)
- L [Liskov Substitution Principle](/modules/10-solid/lsp.md)

Note that the order __SOCDIL__ is not a typo, we're really going through the principles in this order instead to have a more fluid understanding of them. With extra __C__ for learning coupling

## External References

This workshop is basically a very simplified version of the following references. We're only really exploring these principles either for their use or to prepare you when we analyze Laravel's core. We try to immitate real world examples more than what the these references do (which uses Shapes, Animals, geometry or whatever). Reading the following will still help if ever you want to dig in deeper in this principles:

- S [Single Responsibility Principle](https://web.archive.org/web/20150202200348/http://www.objectmentor.com/resources/articles/srp.pdf)
- O [Open/Closed Principle](https://web.archive.org/web/20150905081105/http://www.objectmentor.com/resources/articles/ocp.pdf)
- L [Liskov Substitution](https://web.archive.org/web/20150905081111/http://www.objectmentor.com/resources/articles/lsp.pdf)
- I [Interface Segragation](https://web.archive.org/web/20150905081110/http://www.objectmentor.com/resources/articles/isp.pdf)
- D [Dependency Inversion](https://web.archive.org/web/20150905081110/http://www.objectmentor.com/resources/articles/isp.pdf)
