@extends('Home.layout.master')
@section('content')



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & FAQs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50;
            padding: 10px;
            background-color: #fdfbf4;
        }
        .faq-container {
            max-width: 100%;
            margin: 60px auto 0 ;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .faq-item {
            border-bottom: 1px solid #c2a380;
            padding: 15px 0;
        }
        .faq-item:last-child {
            border-bottom: none;
        }
        .faq-question {
            font-weight: bold;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .faq-answer {
            display: none;
            padding-top: 10px;
            color: #555;
        }
        .faq-question i {
            transition: transform 0.3s ease;
        }
        .faq-question.active i {
            transform: rotate(180deg);
        }
    </style>
</head>
<body>
    <div class="faq-container">
        <br>
        <h1>Frequently Asked Questions</h1><br>
        <p style="text-align: center; color: #777;">We've got your answers right here</p>
        
        <div class="faq-item">
            <div class="faq-question">How can I pay for my order? <i class="fas fa-chevron-down"></i></div>
            <div class="faq-answer">Learn more about all the payment options we offer.</div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">When will I be charged for my order? <i class="fas fa-chevron-down"></i></div>
            <div class="faq-answer">Your card will not be charged until your order ships.</div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">How do I find out about current discounts and promotions? <i class="fas fa-chevron-down"></i></div>
            <div class="faq-answer">Sign up for our emails or text alerts to receive updates.</div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">What shipping and pick up options do you offer? <i class="fas fa-chevron-down"></i></div>
            <div class="faq-answer">We offer various shipping and pick-up solutions to meet your needs.</div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">Can I track my order? <i class="fas fa-chevron-down"></i></div>
            <div class="faq-answer">Yes! You can track your order as a guest or by logging into your account.</div>
        </div>
    </div>
    
    <script>
        document.querySelectorAll(".faq-question").forEach(item => {
            item.addEventListener("click", () => {
                item.classList.toggle("active");
                let answer = item.nextElementSibling;
                answer.style.display = answer.style.display === "block" ? "none" : "block";
            });
        });
    </script>
</body>
</html>

@endsection