<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

// Create a trait for typing functionality
trait TypingIndicator
{
    protected function sayWithTyping($message)
    {
        $this->bot->typesAndWaits(1);
        $this->say($message);
    }

    protected function askWithTyping($question, $next)
    {
        $this->bot->typesAndWaits(1);
        $this->ask($question, $next);
    }
}

class BotmanController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('help', function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->startConversation(new MainMenuConversation());
        });

        $botman->fallback(function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply("Hi! Please type 'help' to start our conversation.");
        });

        $botman->listen();
    }
}

class MainMenuConversation extends Conversation
{
    use TypingIndicator;

    public function run()
    {
        $this->showMainMenu();
    }

    private function showMainMenu()
    {
        $question = Question::create("Please choose one of the following options:")
            ->addButtons([
                Button::create('Reservation Process')->value('reservation'),
                Button::create('Event Details')->value('event_details'),
                Button::create('Cancellations & Modifications')->value('cancellations_modifications'),
                Button::create('Food and Service Customization')->value('food_service_customization'),
                Button::create('Pricing & Payment')->value('pricing_payment'),
                Button::create('Additional Services')->value('additional_services'),
                Button::create('Event Duration')->value('event_duration'),
                Button::create('Legal & Privacy')->value('legal_privacy'),
                Button::create('Miscellaneous')->value('miscellaneous'),
            ]);

        $this->askWithTyping($question, function($answer) {
            switch ($answer->getValue()) {
                case 'reservation':
                    $this->bot->startConversation(new ReservationConversation());
                    break;
                case 'event_details':
                    $this->bot->startConversation(new EventDetailsConversation());
                    break;
                case 'cancellations_modifications':
                    $this->bot->startConversation(new CancellationConversation());
                    break;
                case 'food_service_customization':
                    $this->bot->startConversation(new FoodConversation());
                    break;
                case 'pricing_payment':
                    $this->bot->startConversation(new PricingConversation());
                    break;
                case 'additional_services':
                    $this->bot->startConversation(new AdditionalConversation());
                    break;
                case 'event_duration':
                    $this->bot->startConversation(new EventConversation());
                    break;
                case 'legal_privacy':
                    $this->bot->startConversation(new LegalConversation());
                    break;
                case 'miscellaneous':
                    $this->bot->startConversation(new MissConversation());
                    break;          
                default:
                    $this->sayWithTyping("I didn't understand that option. Let's try again.");
                    $this->showMainMenu();
                    break;
            }
        });
    }
}

class ReservationConversation extends Conversation
{
    use TypingIndicator;

    public function run()
    {
        $question = Question::create("Here are some common questions about the reservation process:")
            ->addButtons([
                Button::create('How can I make a reservation?')->value('how_to_reserve'),
                Button::create('What is the reservation fee?')->value('reservation_fee'),
                Button::create('How far in advance should I book?')->value('booking_advance'),
                Button::create('Can I customize my catering package?')->value('customize_package'),
                Button::create('How can I check the status of my reservation?')->value('check_status'),
                Button::create('How do I know if my preferred event date is available?')->value('check_availability'),
                Button::create('Back')->value('back'),
            ]);

            $this->askWithTyping($question, function($answer) {
                if ($answer->getValue() === 'back') {
                    $this->bot->startConversation(new MainMenuConversation());
                    return;
                }
                $this->handleReservationAnswer($answer->getValue());
            });
    }

    private function handleReservationAnswer($value)
    {
        $responses = [
            'how_to_reserve' => "To make a reservation, simply visit our Packages page, choose the package that suits your event, and select your preferred date on our Calendar. You can confirm your booking by paying the reservation fee through the QR code.",
            'reservation_fee' => "Our reservation fee is 5000 PHP. This fee can be paid easily through the QR code provided during the booking process. The fee will secure your chosen date for your event.",
            'booking_advance' => "We recommend booking at least 1-2 weeks in advance to ensure availability. However, we will do our best to accommodate last-minute requests, depending on our schedule.",
            'customize_package' => "Yes! We offer flexibility in customizing your catering package to fit your event's specific needs. You can choose from various options for food, venue setup, and more. Please feel free to contact us for personalized options.",
            'check_status' => 'You can easily check the status of your reservation by visiting the <a href="https://kylaandkylecatering.com/check-status" target="_parent">View Status</a>. Simply enter your details, and you\'ll be able to track the progress of your booking.',
            'check_availability' => "You can check the availability of your preferred date by visiting our Calendar page. The dates that are clickable mean they are available for booking, while those dates that are not clickable are already fully booked.",
        ];

        if (isset($responses[$value])) {
            $this->sayWithTyping($responses[$value]);
            $this->askForMoreHelp();
        } else {
            $this->sayWithTyping("I didn't understand that option. Let's try again.");
            $this->run();
        }
    }

    private function askForMoreHelp()
    {
        $question = Question::create("Is there anything else I can help you with?")
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'yes') {
                $this->bot->startConversation(new MainMenuConversation());
            } else {
                $this->sayWithTyping("Thank you for chatting with us! If you need assistance in the future, just type 'help' to start a new conversation.");
            }
        });
    }
}

class EventDetailsConversation extends Conversation
{
    use TypingIndicator;

    public function run()
    {
        $question = Question::create("Here are some common questions about event details:")
            ->addButtons([
                Button::create('What is included in the catering package?')->value('included_in_package'),
                Button::create('Do you offer vegetarian or vegan options?')->value('vegan_options'),
                Button::create('How many people can your catering service accommodate?')->value('accommodate_guests'),
                Button::create('Is there a minimum or maximum number of guests?')->value('guest_limit'),
                Button::create('Can I view photos of past events?')->value('view_photos'),
                Button::create('What types of payment methods do you accept?')->value('payment_methods'),
                Button::create('Back')->value('back'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'back') {
                // Return to main menu
                $this->bot->startConversation(new MainMenuConversation());
                return;
            }
            $this->handleEventDetailsAnswer($answer->getValue());
        });
    }

    private function handleEventDetailsAnswer($value)
    {
        $responses = [
            'included_in_package' => "Each catering package includes food, setup, service staff, and cleanup. You can check the details of each package on our Packages page.",
            'vegan_options' => "Yes! We offer a variety of vegetarian and vegan options to cater to different dietary preferences. Let us know your requirements when booking, and we'll customize your menu accordingly.",
            'accommodate_guests' => "Our catering services can accommodate events of all sizes, from small gatherings to large parties. The number of guests we can serve depends on the package and venue size. Please contact us to discuss your specific event details.",
            'guest_limit' => "There is no maximum number of guests for our catering services, but we require a minimum of 100 guests for our catering packages. We suggest discussing your event size with us to choose the best package that fits your needs.",
            'view_photos' => 'Absolutely! Visit our <a href="https://kylaandkylecatering.com/gallery" target="_parent">Gallery page</a> to see photos from previous events we\'ve catered. We take pride in creating beautiful and memorable moments for our clients.',
            'payment_methods' => "We accept payments via bank transfer, over the counter, or through our QR code system for your convenience. Please make sure to complete the payment as outlined in the contract.",
        ];

        if (isset($responses[$value])) {
            $this->sayWithTyping($responses[$value]);
            $this->askForMoreHelp();
        } else {
            $this->sayWithTyping("I didn't understand that option. Let's try again.");
            $this->run();
        }
    }

    private function askForMoreHelp()
    {
        $question = Question::create("Is there anything else I can help you with?")
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'yes') {
                $this->bot->startConversation(new MainMenuConversation());
            } else {
                $this->sayWithTyping("Thank you for chatting with us! If you need assistance in the future, just type 'help' to start a new conversation.");
            }
        });
    }
}

class CancellationConversation extends Conversation
{
    use TypingIndicator;

    public function run()
    {
        $question = Question::create("Here are some common questions about event details:")
            ->addButtons([
                Button::create('What should I do if I need to change my reservation?')->value('change_reservation'),
                Button::create('What happens if I need to cancel my reservation?')->value('cancel_reservation'),
                Button::create('What happens if I need to reschedule my event?')->value('reschedule_event'),
                Button::create('What is your cancellation policy?')->value('cancellation_policy'),
                Button::create('Are there any exceptions to the cancellation or rescheduling fees?')->value('exceptions'),
                Button::create('What is a pencil booking, and how long is it valid?')->value('pencil_booking'),
                Button::create('Back')->value('back'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'back') {
                // Return to main menu
                $this->bot->startConversation(new MainMenuConversation());
                return;
            }
            $this->handleReservationAnswer($answer->getValue());
        });
    }

    private function handleReservationAnswer($value)
    {
        $responses = [
            'change_reservation' => "If you need to modify your reservation, please contact us as soon as possible, and we will assist you in adjusting your booking based on availability.",
            'cancel_reservation' => "If you need to cancel your reservation, please notify us as soon as possible. Cancellation policies apply, and a portion of the reservation fee may be non-refundable depending on the timing of the cancellation.",
            'reschedule_event' => "If you reschedule your event 3 days or fewer before the scheduled date, the reservation fee of ₱5,000 will be non-refundable. For events with 500 or more attendees, a 5% fee will be deducted from the deposit for rescheduling.",
            'cancellation_policy' => "If you cancel your event 3 days or fewer before the scheduled date, you will be charged 10% of the total contract amount to cover preparations made by the caterer. However, in the case of severe weather, death, or extraordinary circumstances, these fees may be waived.",
            'exceptions' => 'Yes, rescheduling and cancellation fees will be waived in cases of severe weather conditions (e.g., typhoons), death, or other extraordinary circumstances. Please notify us as soon as possible in these situations.',
            'pencil_booking' => "A pencil booking is a tentative reservation held for up to 1 week. If another client makes a payment to secure the same date, the pencil booking may be released. Clients who pay the reservation fee are prioritized over pencil bookings.",
        ];

        if (isset($responses[$value])) {
            $this->sayWithTyping($responses[$value]);
            $this->askForMoreHelp();
        } else {
            $this->sayWithTyping("I didn't understand that option. Let's try again.");
            $this->run();
        }
    }

    private function askForMoreHelp()
    {
        $question = Question::create("Is there anything else I can help you with?")
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'yes') {
                $this->bot->startConversation(new MainMenuConversation());
            } else {
                $this->sayWithTyping("Thank you for chatting with us! If you need assistance in the future, just type 'help' to start a new conversation.");
            }
        });
    }
}

class FoodConversation extends Conversation
{
    use TypingIndicator;

    public function run()
    {
        $question = Question::create("Here are some common questions about event details:")
            ->addButtons([
                Button::create('Can I make changes to my menu after booking?')->value('change_menu'),
                Button::create('Do you provide equipment for events?')->value('event_equipment'),
                Button::create('Can you accommodate special dietary needs or allergies?')->value('special_dietary_needs'),
                Button::create('Do you offer event planning services?')->value('event_planning'),
                Button::create('Back')->value('back'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'back') {
                // Return to main menu
                $this->bot->startConversation(new MainMenuConversation());
                return;
            }
            $this->handleReservationAnswer($answer->getValue());
        });
    }

    private function handleReservationAnswer($value)
    {
        $responses = [
            'change_menu' => "Yes, you can make changes to your menu after booking. Please notify us at least 3-5 days before your event, and we will do our best to accommodate your requests based on availability.",
            'event_equipment' => "Yes! In addition to food and services, we also provide equipment such as tables, chairs, and other event essentials. For more details, please inquire during the reservation process or on our Packages page.",
            'special_dietary_needs' => "Yes, we can accommodate various dietary needs, including food allergies and intolerances. Please inform us of any special requests when you make your reservation, and we’ll do our best to provide safe and delicious options for all your guests.",
            'event_planning' => "While we specialize in catering services, we can assist with event planning by providing recommendations for venue setups, decor, and menu options to ensure your event is a success. For more detailed event planning, we recommend contacting a professional event planner.",
        ];

        if (isset($responses[$value])) {
            $this->sayWithTyping($responses[$value]);
            $this->askForMoreHelp();
        } else {
            $this->sayWithTyping("I didn't understand that option. Let's try again.");
            $this->run();
        }
    }

    private function askForMoreHelp()
    {
        $question = Question::create("Is there anything else I can help you with?")
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'yes') {
                $this->bot->startConversation(new MainMenuConversation());
            } else {
                $this->sayWithTyping("Thank you for chatting with us! If you need assistance in the future, just type 'help' to start a new conversation.");
            }
        });
    }
}

class PricingConversation extends Conversation
{
    use TypingIndicator;

    public function run()
    {
        $question = Question::create("Here are some common questions about event details:")
            ->addButtons([
                Button::create('Are the prices on the website final, or are there additional charges?')->value('final_prices'),
                Button::create('What is the payment schedule for my reservation?')->value('payment_schedule'),
                Button::create('Back')->value('back'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'back') {
                // Return to main menu
                $this->bot->startConversation(new MainMenuConversation());
                return;
            }
            $this->handleReservationAnswer($answer->getValue());
        });
    }

    private function handleReservationAnswer($value)
    {
        $responses = [
            'final_prices' => "The prices listed on our website are generally final, but additional charges may apply for extra services or customizations, such as special requests for food or decor. For a final total, please contact or reach out to us during the booking process, and we’ll provide a complete breakdown of any additional costs.",
            'payment_schedule' => "To confirm your reservation, a ₱5,000 non-refundable deposit is due. The remaining balance is due in two parts: 50% down payment is required 1 week prior to the event, and the final balance is due on the day of the event.",
        ];

        if (isset($responses[$value])) {
            $this->sayWithTyping($responses[$value]);
            $this->askForMoreHelp();
        } else {
            $this->sayWithTyping("I didn't understand that option. Let's try again.");
            $this->run();
        }
    }

    private function askForMoreHelp()
    {
        $question = Question::create("Is there anything else I can help you with?")
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'yes') {
                $this->bot->startConversation(new MainMenuConversation());
            } else {
                $this->sayWithTyping("Thank you for chatting with us! If you need assistance in the future, just type 'help' to start a new conversation.");
            }
        });
    }
}

class AdditionalConversation extends Conversation
{
    use TypingIndicator;

    public function run()
    {
        $question = Question::create("Here are some common questions about event details:")
            ->addButtons([
                Button::create('Can I bring my own food or drinks to the event?')->value('own_food_drinks'),
                Button::create('Do you offer food trays?')->value('food_trays'),
                Button::create('Do you offer all-in packages that include venues, hosts, or photographers?')->value('all_in_packages'),
                Button::create('Can I request specific serving times for my event?')->value('serving_times'),
                Button::create('Back')->value('back'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'back') {
                // Return to main menu
                $this->bot->startConversation(new MainMenuConversation());
                return;
            }
            $this->handleReservationAnswer($answer->getValue());
        });
    }

    private function handleReservationAnswer($value)
    {
        $responses = [
            'own_food_drinks' => "Yes, you are welcome to bring your own food or drinks to the event. Please inform us in advance so we can assist with providing additional tables or food pans if necessary.",
            'food_trays' => "No, we do not offer food trays at the moment. However, we provide full catering services with various package options that include food, setup, and staff to ensure your event runs smoothly.",
            'all_in_packages' => "No, we do not offer all-in packages that include venues, hosts, or photographers. We specialize in catering services, but we can recommend trusted partners for additional services like venue booking, event hosting, and photography. Feel free to reach out for more details.",
            'serving_times' => "Yes, you can request specific serving times for your event. Please let us know your preferred schedule when making the reservation, and we will do our best to accommodate your timing needs within the duration of the catering service.",
        ];

        if (isset($responses[$value])) {
            $this->sayWithTyping($responses[$value]);
            $this->askForMoreHelp();
        } else {
            $this->sayWithTyping("I didn't understand that option. Let's try again.");
            $this->run();
        }
    }

    private function askForMoreHelp()
    {
        $question = Question::create("Is there anything else I can help you with?")
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'yes') {
                $this->bot->startConversation(new MainMenuConversation());
            } else {
                $this->sayWithTyping("Thank you for chatting with us! If you need assistance in the future, just type 'help' to start a new conversation.");
            }
        });
    }
}

class EventConversation extends Conversation
{
    use TypingIndicator;

    public function run()
    {
        $question = Question::create("Here are some common questions about event details:")
            ->addButtons([
                Button::create('How long will catering services be provided during the event?')->value('catering_duration'),
                Button::create('Back')->value('back'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'back') {
                // Return to main menu
                $this->bot->startConversation(new MainMenuConversation());
                return;
            }
            $this->handleReservationAnswer($answer->getValue());
        });
    }

    private function handleReservationAnswer($value)
    {
        $responses = [
            'catering_duration' => "Our standard catering services last up to 5 hours from the scheduled start time of the event. If you need to extend the service duration, an additional charge will apply.",
        ];

        if (isset($responses[$value])) {
            $this->sayWithTyping($responses[$value]);
            $this->askForMoreHelp();
        } else {
            $this->sayWithTyping("I didn't understand that option. Let's try again.");
            $this->run();
        }
    }

    private function askForMoreHelp()
    {
        $question = Question::create("Is there anything else I can help you with?")
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'yes') {
                $this->bot->startConversation(new MainMenuConversation());
            } else {
                $this->sayWithTyping("Thank you for chatting with us! If you need assistance in the future, just type 'help' to start a new conversation.");
            }
        });
    }
}

class LegalConversation extends Conversation
{
    use TypingIndicator;

    public function run()
    {
        $question = Question::create("Here are some common questions about event details:")
            ->addButtons([
                Button::create('Is my personal information kept private?')->value('personal_information_privacy'),
                Button::create('What laws govern this agreement?')->value('governing_laws'),
                Button::create('Back')->value('back'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'back') {
                // Return to main menu
                $this->bot->startConversation(new MainMenuConversation());
                return;
            }
            $this->handleReservationAnswer($answer->getValue());
        });
    }

    private function handleReservationAnswer($value)
    {
        $responses = [
            'personal_information_privacy' => "Yes, we value your privacy. All personal information provided will be used solely for event planning, communication, and improving our services. Your information will be kept secure and confidential, and will not be shared with third parties unless required by law or with your permission.",
            'governing_laws' => "This Catering Agreement is governed by and construed in accordance with the laws of the Republic of the Philippines.",
        ];

        if (isset($responses[$value])) {
            $this->sayWithTyping($responses[$value]);
            $this->askForMoreHelp();
        } else {
            $this->sayWithTyping("I didn't understand that option. Let's try again.");
            $this->run();
        }
    }

    private function askForMoreHelp()
    {
        $question = Question::create("Is there anything else I can help you with?")
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'yes') {
                $this->bot->startConversation(new MainMenuConversation());
            } else {
                $this->sayWithTyping("Thank you for chatting with us! If you need assistance in the future, just type 'help' to start a new conversation.");
            }
        });
    }
}

class MissConversation extends Conversation
{
    use TypingIndicator;

    public function run()
    {
        $question = Question::create("Here are some common questions about event details:")
            ->addButtons([
                Button::create('Do you provide a food tasting session before the event?')->value('tasting_session'),
                Button::create('How can I contact you for more information?')->value('contact_info'),
                Button::create('What areas do you serve?')->value('service_areas'),
                Button::create('Where are you located?')->value('location'),
                Button::create('Back')->value('back'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'back') {
                // Return to main menu
                $this->bot->startConversation(new MainMenuConversation());
                return;
            } else {
                $this->handleReservationAnswer($answer->getValue());
            }
        });
    }

    private function handleReservationAnswer($value)
    {
        $responses = [
            'tasting_session' => "Yes! We offer a tasting session for clients to sample our menu before confirming their reservation. Please reach out to schedule your tasting appointment at least a week before your event.",
            'contact_info' => "You can reach us directly through our Contact page on the website. We have a contact form available, and you can also find our email and phone number there for faster communication.",
            'service_areas' => "We primarily serve the Montalban area and surrounding regions. If you're unsure whether we cater to your location, feel free to reach out to us, and we'll be happy to assist you!",
            'location' => "We are located at 428 Cacho St., Brgy. Balite, Rodriguez, Rizal. You can also find us easily on Waze and Google Maps.",
        ];

        if (isset($responses[$value])) {
            $this->sayWithTyping($responses[$value]);
            $this->askForMoreHelp();
        } else {
            $this->sayWithTyping("I didn't understand that option. Let's try again.");
            $this->run();
        }
    }

    private function askForMoreHelp()
    {
        $question = Question::create("Is there anything else I can help you with?")
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->askWithTyping($question, function($answer) {
            if ($answer->getValue() === 'yes') {
                $this->bot->startConversation(new MainMenuConversation());
            } else {
                $this->sayWithTyping("Thank you for chatting with us! If you need assistance in the future, just type 'help' to start a new conversation.");
            }
        });
    }
}
