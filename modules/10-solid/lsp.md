# Liskov Substitution

This is one of the very advanced areas of SOLID and not much use YET when beginning to understand Laravel. I'll explain about it in a short while but let's not dig too deep with it.

Robert C. Martin describes this principle as soft rule that "functions that use pointers to base classes must be able to use objects of derived classes without knowing it."

Liskov substitution is somewhat standardizing the way we do polymorphism and defining inheritance.

![Duck on Liskov Substitution](https://image.ibb.co/cyshi7/lsp.jpg)

When first learning about object oriented programming, inheritance is usually described as an "is a" relationship. If a penguin "is a" bird, then the Penguin class should inherit from the Bird class. The "is a" technique of determining inheritance relationships is simple and useful, but occasionally results in bad use of inheritance.

The principle defines that __objects of a superclass shall be replaceable with objects of its subclasses without breaking the application__. That requires the objects of your subclasses to behave in the same way as the objects of your superclass. You can achieve that by following a few rules, which are pretty similar to the [design by contract](https://en.wikipedia.org/wiki/Design_by_contract) concept defined by Bertrand Meyer.