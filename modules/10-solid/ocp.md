# Open/Closed Principle

Robert C. Martin considered this principle as the “the most important principle of object-oriented design”. But he wasn’t the first one who defined it. Bertrand Meyer wrote about it in 1988 in his book [Object-Oriented Software Construction](https://en.wikipedia.org/wiki/Object-Oriented_Software_Construction). He explained the Open/Closed Principle as:

__“Software entities (classes, modules, functions, etc.) should be open for extension, but closed for modification.”__ - Bertrand Meyer

__Open for extension__

This means that the behavior of the module can be extended. That we can make
the module behave in new and different ways as the requirements of the application change, or to meet the needs of new applications.

__Closed for modification__

The source code of such a module is inviolate. No one is allowed to make source
code changes to it.

In order to achieve Open/Closed code, __abstraction is key__.

Consider the __"bad"__ code below:

```php

class PayrollProcessingService
{
    function process(Employee $employee)
    {
        if ($employee->payrollType === PayrollType::MONTHLY) {
            //  does things for monthly payment
        } else if ($employee->payrollType === PayrollType::SEMI_MONTHLY) {
            //  does things for monthly payment
        }
    }
}

```

In payrol systems, you have to account for different types of payroll to process. There are employees that are paid:
1. Daily - these are normally construction workers.
2. Weekly - haven't encountered this yet but BIR has computation for this so employees like these should exist in the Philippines.
3. Semi Monthly - these are the common ones, likely how you're paid.
4. Monthly - are still actually paid twice a month but their computation is different.
5. Special cases
    - Employees paid by hour, normally freelancers or outsourced people.
    - Consultants
    - Piece Work - either freelancers, or factory workers from industries like manufacturing.

Consider the scenario where your boss tells you to add feature to add support for employees being paid daily. What you will do in this source code is to update the PayrollProcessor class and create another else if with code inside.

The problem with this is that it can possibly cause regression and create bugs in previously working code for monthly payment and semi monthly payment. It also creates the code smell that your code is too large.

To combat this issue, we __"open"__ our code for extension and __"close"__ it from modifications.

To do this, we'll introduce a common interface called `PayrollProcessor` that where we can describe multiple `implementations` of. In order to switch between the implementations, we'll have to define a `context` called `PayrollProcessorContext` to determine the correct implementation for us. As you've guessed, this is the strategy design pattern.

```php

interface PayrollProcessor
{
    function process(Employee $employee);
}

class MonthlyPaidPayrollProcessor implements PayrollProcessor
{
    function process(Employee $employee) {/* code for monthly payment here ... */}
}

class SemiMonthlyPaidPayrollProcessor implements PayrollProcessor
{
    function process(Employee $employee) {/* code for semi-monthly payment here ... */}
}

class PayrollProcessorContext
{
    function getProcessorFor($payrollType)
    {
        switch($payrollType) {
            case PayrollType::MONTHLY: 
                return new MonthlyPaidPayrollProcessor();
            case PayrollType::SEMI_MONTHLY: 
                return new SemiMonthlyPaidPayrollProcessor();
            default: 
                throw PayrollProcessException::fromInvalidPayrollType();
        }
    }
}

class PayrollProcessingService
{
    function process(Employee $employee)
    {
        $processor = $this->context->getProcessorFor($employee->payrollType);
        return $processor->process($employee);
    }
}
```

Now, if we need to add new functionality (open to extension), we'll just have to define a new implementation and map it in the context:

```php
class DailyPaidPayrollProcessor implements PayrollProcessor
{
    function process(Employee $employee) {/* code for daily payment here ... */}
}

class PayrollProcessorContext
{
    function getProcessorFor($payrollType)
    {
        switch($payrollType) {
            case PayrollType::MONTHLY: 
                return new MonthlyPaidPayrollProcessor();
            case PayrollType::SEMI_MONTHLY: 
                return new SemiMonthlyPaidPayrollProcessor();
            case PayrollType::DAILY: 
                return new DailyPaidPayrollProcessor();
            default: 
                throw PayrollProcessException::fromInvalidPayrollType();
        }
    }
}
```

