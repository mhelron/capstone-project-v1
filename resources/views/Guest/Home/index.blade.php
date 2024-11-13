@extends('layouts.guestlayout')

@section('content')

<div class="container">

<div class="container" style="padding-top: 50px;">

    <div class="row align-items-center pt-4">
        <div class="col-md-6">
       
        <h3 style="color: solid black; font-family: 'Monaco'; border-bottom: 4px solid darkorange; padding-bottom:
        3px;">Kyla and Kyle Catering Services</h3>

            <h1 style="font-weight: 900; font-family: 'Georgia', sans-serif; font-size: 48px; padding-top: 5px;">One of 
            the most known Catering Services in Montalban</h1>
            <p>At Kyla and Kyle Catering Services, we bring people together through great food and
            exceptional service. Based in Rodriguez, Rizal, we specialize in creating unforgettable
            experiences for every occasion, from weddings and corporate events to family
            celebrations. 
            </p>

            <p>With our skilled team and attention to detail, we handle everything from
            venue setup to delicious, carefully prepared meals. Let us bring your vision to life and
            make your next event truly special.</p>
            
            <div class="d-flex mt-4 mb-4">
                <a href="{{route('guest.reserve')}}" class="btn btn-darkorange me-3">Reserve</a>
                <a href="#" class="btn btn-darkorange">View Status</a>
            </div>
        </div>

        <!-- Image Carousel with Fade Effect -->
        <div class="col-md-6 mt-4 mb-5">
            <div id="imageCarousel" class="carousel slide carousel-fade overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/Home/image1.png') }}" class="d-block w-100 carousel-image" alt="Wedding Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/Home/image2.png') }}" class="d-block w-100 carousel-image" alt="Debut Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/Home/image3.png') }}" class="d-block w-100 carousel-image" alt="Baptismal Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/Home/image4.png') }}" class="d-block w-100 carousel-image" alt="Corporate Event Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/Home/image5.png') }}" class="d-block w-100 carousel-image" alt="Birthday Image">
                    </div>
                </div>

                <!-- Carousel Arrows -->
                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev" aria-label="Previous Slide">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next" aria-label="Next Slide">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>


                <!-- Circle Index Indicators -->
                <div class="carousel-indicators-container">
                    <div class="carousel-indicator" data-bs-target="#imageCarousel" data-bs-slide-to="0"></div>
                    <div class="carousel-indicator" data-bs-target="#imageCarousel" data-bs-slide-to="1"></div>
                    <div class="carousel-indicator" data-bs-target="#imageCarousel" data-bs-slide-to="2"></div>
                    <div class="carousel-indicator" data-bs-target="#imageCarousel" data-bs-slide-to="3"></div>
                    <div class="carousel-indicator" data-bs-target="#imageCarousel" data-bs-slide-to="4"></div>
                </div>
            </div>
        </div>
    </div>
        <!-- Chatbot Bubble -->
        <div class="chatbot-container">
            <div class="chatbot-bubble" onclick="toggleChat()">
                <i class="bi bi-chat-square-dots"></i>
            </div>
            <div class="chatbot-chatbox" id="chatbox">
                <div class="chatbox-header">
                    <h5>Chat with us!</h5>
                    <button class="btn-close-chatbox" onclick="toggleChat()">×</button>
                </div>
                <div class="chatbox-body" id="chatboxBody">
                    <!-- Initially shows the Start Conversation button -->
                    <button id="startConversationBtn" onclick="startConversation()" class="start-conversation-btn">Start Conversation</button>
                </div>
                <div class="chatbox-footer">
                    <div class="chatbox-restart-back">
                        <button onclick="restartConversation()">Restart</button>
                        <button onclick="goBack()">Back</button>
                    </div>
                    <div class="chatbox-send">
                        <input type="text" id="userMessage" placeholder="Type a message..." onkeypress="handleMessage(event)" disabled>
                        <button onclick="sendMessage()" disabled>Send</button>
                    </div>
                </div>
            </div>
        </div>

</div>

<!-- Add Bootstrap Icons for the chatbot bubble -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</div>

<style>
  @media (max-width: 768px) {
        .responsive-heading {
            padding-top: 1000px;
        }
    }
  
/* The carousel container */
#imageCarousel {
    position: relative;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.5); /* Dark shadow for outer box */
    border-top-right-radius: 100px; /* Rounded top-right corner */
    border-bottom-left-radius: 100px; /* Rounded bottom-left corner */  
    border-top-left-radius: 0; /* Normal top-left corner */
    border-bottom-right-radius: 0; /* Normal bottom-right corner */
}

/* Create a wrapper for the image that clips the shadow around the rounded corners */
.carousel-image-wrapper {
    overflow: hidden; /* Ensures that the shadow is clipped around the image's rounded corners */
    border-top-right-radius: 100px; /* Rounded top-right corner */
    border-bottom-left-radius: 100px; /* Rounded bottom-left corner */
}

/* The carousel image itself */
.carousel-image {
    height: 600px; /* Fixed height */
    width: 100%; /* Responsive width */
    object-fit: cover; /* Ensures the image covers the area without stretching */
    border-top-left-radius: 0; /* No rounding on the top-left corner */
    border-bottom-right-radius: 0; /* No rounding on the bottom-right corner */
    border: 2px solid darkorange; /* Thin black border around the image */
}

/* Apply the orange shadow to the outer shadow of the carousel container */
#imageCarousel {
    box-shadow: 0px 0px 10px 5px rgba(255, 87, 34, 0.7); /* Darker orange shadow */

}

/* Custom Circle Indicators */
.carousel-indicators-container {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 100;
}

.carousel-indicator {
    width: 12px;
    height: 12px;
    background-color: darkorange;
    border-radius: 50%;
    opacity: 0.6;
    cursor: pointer;
    transition: opacity 0.3s, transform 0.3s;
}

.carousel-indicator.active {
    opacity: 1;
    transform: scale(1.3); /* Slightly enlarge the active indicator */
}

.carousel-indicator:hover {
    opacity: 1;
    background-color: #ff7f00; /* Change color on hover */
}

/* Chatbot Bubble Styles */
        /* Chatbot Bubble Styles */
        .start-conversation-btn {
            background-color: transparent; /* Transparent background */
            color: #ff5722; /* Orange text color */
            padding: 12px 24px;
            border: 2px solid #ff5722; /* Border color to match the text */
            border-radius: 30px; /* Rounded corners */
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin-top: 10px;
            display: block; /* Ensures the button is centered */
            margin-left: auto;
            margin-right: auto;
        }

        /* Hover Effect for Start Conversation button */
        .start-conversation-btn:hover {
            background-color: #ff5722; /* Orange background on hover */
            color: white; /* White text on hover */
        }

        /* Disable the input and send button initially */
        .chatbox-send input:disabled,
        .chatbox-send button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .chatbot-container {
            position: fixed;
            bottom: 160px; /* Move the bubble higher */
            right: 30px;
            z-index: 1000; /* Ensures the chatbot is above other content */
        }

        .chatbot-bubble {
            background-color: #ff5722;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }

        .chatbot-bubble:hover {
            transform: scale(1.1);
        }

        /* Chatbox Main Container */
        .chatbot-chatbox {
            display: none; /* Initially hidden */
            position: fixed;
            bottom: 230px; /* Chatbox above the footer */
            right: 30px;
            width: 300px;
            height: 450px; /* Adjusted height for additional buttons */
            background-color: #f1f1f1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            z-index: 1001;
            border: 2px solid #ff5722;
            display: flex;
            flex-direction: column; /* Stack header, body, and footer vertically */
        }

        /* Chatbox Header */
        .chatbox-header {
            background-color: #ff5722;
            color: white;
            padding: 10px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbox-header h5 {
            margin: 0;
        }

        .btn-close-chatbox {
            background-color: #ff5722;
            color: white;
            font-size: 30px;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }

        .btn-close-chatbox:hover {
            background-color: #ff3d00;
        }

        /* Chatbox Body */
        .chatbox-body {
            padding: 10px;
            overflow-y: auto;
            flex: 1;
            font-size: 14px;
            max-height: calc(100% - 120px); /* Allow space for header + footer */
        }

        /* Chatbox Footer */
        .chatbox-footer {
            display: flex;
            flex-direction: column; /* Stack buttons vertically */
            padding: 10px;
            background-color: #f1f1f1;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        /* Buttons in Footer (Restart and Send) */
        .chatbox-restart-back {
            display: flex;
            flex-direction: column; /* Stack buttons vertically on mobile */
            gap: 10px; /* Add space between the buttons */
            margin-bottom: 10px; /* Space between Back/Restart and Send button */
        }

        .chatbox-restart-back button {
            background-color: #ff5722;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%; /* Make both buttons take up full width */
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .chatbox-restart-back button:hover {
            background-color: #ff3d00;
        }

        /* Input Field and Send Button */
        .chatbox-send {
            display: flex;
            flex-direction: column; /* Stack input and send button vertically */
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .chatbox-send input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
        }

        .chatbox-send input:focus {
            border-color: #ff5722;
        }

        .chatbox-send button {
            background-color: #ff5722;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            width: 100%; /* Ensure send button also takes full width */
        }

        .chatbox-send button:hover {
            background-color: #ff3d00;
        }

       

        /* Mobile Responsiveness */
        @media (max-width: 576px) {
            .chatbot-chatbox {
                width: 250px;
                height: 400px;
            }

            .chatbox-send input {
                font-size: 12px;
            }

            .chatbox-send button {
                font-size: 12px;
            }

            .chatbox-restart-back button {
                font-size: 12px;
            }

            /* Stack footer buttons vertically */
            .chatbox-footer {
                flex-direction: column; /* Stack footer buttons on top of each other */
            }
        }

</style>

<script>
// JavaScript to update active class on carousel item change
const carousel = document.querySelector('#imageCarousel');
const indicators = document.querySelectorAll('.carousel-indicator');

carousel.addEventListener('slide.bs.carousel', (event) => {
    const activeIndex = event.to;
    indicators.forEach((indicator, index) => {
        if (index === activeIndex) {
            indicator.classList.add('active');
        } else {
            indicator.classList.remove('active');
        }
    });
});

// Optionally, handle clicking the circle to change the slide
indicators.forEach((indicator) => {
    indicator.addEventListener('click', (e) => {
        const slideToIndex = e.target.getAttribute('data-bs-slide-to');
        const carouselInstance = new bootstrap.Carousel(carousel);
        carouselInstance.to(parseInt(slideToIndex));
    });
});
//CHATBOT

// Function to start the conversation and display the welcome message
function startConversation() {
    const chatboxBody = document.querySelector(".chatbox-body");
    const startConversationBtn = document.getElementById("startConversationBtn");
    const userMessageInput = document.getElementById("userMessage");
    const sendButton = document.querySelector(".chatbox-send button");

    // Disable the "Start Conversation" button after click
    startConversationBtn.disabled = true;
    startConversationBtn.style.display = "none";  // Hide the "Start Conversation" button

    // Enable the user input and send button
    userMessageInput.disabled = false;
    sendButton.disabled = false;

    // Display the bot's welcome message
    chatboxBody.innerHTML += `<p><strong>Bot:</strong> Welcome to Kyla and Kyle Catering Chatbot, How can I help you?</p>`;
    chatboxBody.scrollTop = chatboxBody.scrollHeight;
}

    // Toggle chatbox visibility
let previousConversation = "";  // Variable to store the last conversation state

// Toggle chatbox visibility
function toggleChat() {
    const chatbox = document.getElementById("chatbox");
    chatbox.style.display = (chatbox.style.display === "none" || chatbox.style.display === "") ? "block" : "none";
}

// Function to handle sending a message when Enter key is pressed
function handleMessage(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

// Function to send a message and display bot's response
function sendMessage() {
    const userMessage = document.getElementById("userMessage").value.trim();
    const chatboxBody = document.querySelector(".chatbox-body");

    if (userMessage === "") {
        return; // Don't send empty messages
    }

    // Save current conversation before sending a new message (for the "Back" button)
    previousConversation = chatboxBody.innerHTML;

    // Display user message
    chatboxBody.innerHTML += `<p><strong>You:</strong> ${userMessage}</p>`;
    chatboxBody.scrollTop = chatboxBody.scrollHeight;

    // Simulate a bot response after a delay
    setTimeout(() => {
        chatboxBody.innerHTML += `<p><strong>Bot:</strong> I'm sorry, I can only give basic responses for now. How can I help you?</p>`;
        chatboxBody.scrollTop = chatboxBody.scrollHeight;
    }, 1000);

    // Clear input field
    document.getElementById("userMessage").value = "";
}

// Function to restart the conversation by clearing chat history and resetting the bot's message
function restartConversation() {
    const chatboxBody = document.getElementById("chatboxBody");
    const userMessageInput = document.getElementById("userMessage");

    // Clear chat history
    chatboxBody.innerHTML = "<p><strong>Bot:</strong> Welcome to Kyla and Kyle Catering Chatbot, How can I assist you today?</p>";
    
    // Clear the input field
    userMessageInput.value = "";
    
    // Scroll chatbox to the bottom to see the latest message
    chatboxBody.scrollTop = chatboxBody.scrollHeight;

    // Clear the stored previous conversation
    previousConversation = "";
}

// Function to go back to the last conversation before sending a message
function goBack() {
    const chatboxBody = document.getElementById("chatboxBody");

    if (previousConversation) {
        // Restore the previous conversation if it exists
        chatboxBody.innerHTML = previousConversation;
        chatboxBody.scrollTop = chatboxBody.scrollHeight; // Scroll to bottom
    }
}
// Automatically close the chatbox when the page loads
window.onload = function() {
    const chatbox = document.getElementById('chatbox');
    chatbox.style.display = 'none'; // Make sure the chatbox is hidden initially
};

</script>

@endsection
