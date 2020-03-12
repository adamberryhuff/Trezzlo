# Trezzlo
Trezzlo is a simple Laravel-based application that enables businesses to obtain qualty reviews and collect inbound leads.

## How it works
Upon onboarding each user of Trezzlo is assigned a Twilio number. The business owner can then prompt their customers to send an SMS to the number to leave their feedback; sometimes offering an incentive for feedback. If the feedback is positive, Trezzlo will prompt the customer to leave a review on a review site the business owner provides when they sign up such as Google or TripAdvisor. If the feedback is negative, the customer will be directed to a form where they can leave feedback regarding their experience at the business. The feedback is then sent to the business owner. In this way, positive feedback will generate reviews and negative feedback will be funneled directly to the business owner so they can follow-up with the customer. All customers are saved into a database to be used as leads later. The idea for version 2 is that the business owner would be able to send promotions to users who left positive feedback in the future.

## Integrations
- Slack
- Twilio
- Amazon SES
