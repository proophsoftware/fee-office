# Interviewing Domain Experts

On the previous page we got some insights of the Fee Office domain. @enumag provided the
information from a developer point of view. However, he is our domain expert at the moment and
that's a common scenario in the real world, too. Often developers working for the company for a long
time are the best domain experts. They've built the legacy systems and dealt with the business logic for
many years. They know all the edge cases.

{.alert .alert-warning}
It doesn't mean you should only talk to developers.
Their view of the business is often driven by a technical mindset. Find a way to get access to real domain experts
as often as possible. Usually they are very busy, but try it anyway.

And like a true domain expert @enumag provided a lot of information in one go. When talking to domain experts
you need to be prepared. One good question can cause a long discussion. You can get access to very important knowledge
but the domain experts will use their language and you probably won't understand everything right away.

{.alert .alert-info}
[@cyriux](https://github.com/cyriux){: class="alert-link"} gave a great talk about the topic at DDD Europe 2016:
[Interviewing Domain Experts: heuristics from the trenches](https://www.youtube.com/watch?v=XYw5Mn5yVMM){: class="alert-link"}

Given the advices of the talk linked above we can take @enumag's domain introduction, group the information and highlight
key phrases that sound important.

## Developer Notes

### [Supporting] RealtyRegistration

Building- > Entrance -> Appartement -> Contract

The business is about providing **accountancy services** to the **owners of large buildings** or **organizations that take care of the building** when **each apartment is owned by a different person.**

The first thing to add is a **building, it's entrances** (*each entrance has a different address*) and the **apartments in the building.**

**ApartmentAttributes** are properties of the apartment such as *bodycount (how many people live in the apartment at the time)* **which can change every now and then.** *!!!Attention!!!* -> Each change is represented by a new ApartmentAttributeValue.

Then we need to add the **contracts - who owns and who lives in each apartment.**

#### From Import

When adding a new building, most of the data about apartments, contracts and people are loaded from an external database or an import file.

### [Supporting] FeeRecipe

Next we need to tell the system what **fees should the people pay and how to calculate the exact amounts** (since they can differ each month but can be calculated). This is represented by the FeeRecipe entity.

Each *contract* usually has around 5-10 *FeeRecipes*.

*there is a FinancialAccount entity between Contract and FeeRecipe. Most often each contract only has one FinancialAccount but in some edge cases there can be more. This is required because the Fees on different FinancialAccounts are treated a bit differently in some cases based on the attributes of the FinancialAccount but the details are unimportant.*

### [Supporting] Accounting

Next looking from the other side of the diagram there is an **AccountingOrganization**. In most cases an *AccountingOrganizations is 1:1 with Building* but **there are exceptions** with *one AccountingOrganization handling multiple buildings and also exceptions with one building being split to multiple AccountingOrganizations.* Therefore we made them completely independent. **+1**

### [Supporting] ContactAdministration

Finally the ContactCard represents a Person or Company and can be referenced pretty much anywhere in the system. It can be an employee of our company, an owner of an apartment, an organization managing a building, a contractor our company cooperates with etc.

### [Supporting] PaymentCollecting?
Maybe this context and FeeCalculating belong to the same core context?

### [Core] FeeCalculating

These people need to pay some fees every months which is where our company comes in - to help determine what fees they should pay and observe if they are indeed paying them.

At the **end of each month** an automated process takes each (still **active**) **FeeRecipe** and generates a new **Fee for it for that month**. The calculation requires the **formula from the FeeRecipe** and the **current ApartmentAttributeValues on the related Appartment.**

#### Needs Clarification

*FinancialAccountGroup is actually a brand new entity we came up with just this morning after discovering some serious problems in our PaymentAllocations process. Basically it's a group of FinancialAccounts that holds some options how should the Payments be allocated to Fees. FinancialAccountGroup is actually the scope for the PaymentAllocations process - take all not-fully-allocated Payments and FeeItems related to the FinancialAccountGroup and do the process described in the previous issue.*

### Payment Import

PaymentPreference holds a **reference number** that we can use to **match a given payment** from the import to a **specific person and FinancialAccountGroup**. The process of matching imported payments to people **converts UnassignedPayment to Payment**.

## Explanation of the notes

I've grouped the information by context. Please keep in mind that this grouping is only based on my very
first understanding of the domain. It's not the final result. In the demo you find a slightly different set of contexts.
Anyway, it's a starting point to gain knowledge and connect the dots. I've highlighted phrases and words that seem important.
A good basis for further questions.

*On the next page we'll create a first context map.*