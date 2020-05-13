@extends('layouts.master')
@section('title', 'How it works')

@section('content')

<div class="bg-light-custom">

    <div class="container hiw-page">
        <div class="row my-5 margin-top-100 margin-bottom-100 justify-content-center align-items-center">
            <div class="col-md-6">
                <h3 class="font-weight-light hiw-header">How Yohli Works</h3>
                <h1 class="hiw-big-text hiw-header">
                    Get the most from Yohli and live your work dream.
                </h1>
                <p>Yohli connects clients to expert freelancers who are available to hire by the hour or job.</p>
            </div>
            <div class="col-md-6">
                <img class="floating img-center img-fluid header-img" src="{{ asset('assets/images/ill/question.svg') }}">
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <section class="tab js-tab">
                  <article class="tab__content" id="tab-one">
                    <h1 class="tab__title" data-tab="header">Yohli Hirers</h1>
                    <div class="tab__body" data-tab="body">
                        <div class="row align-items-center">
                            <div class="col-md-6 col-sm-12 order-lg-2 py-5">
                                <img class="img-center" src="{{ asset("assets/images/how_it_works/1.png") }}">
                            </div>
                            <div class="col-md-6 col-sm-12 order-lg-1">
                                <h1 class="section-head">Finding the right matches</h1>
                                <p class="pt-2">On Yohli you’ll find a range of top freelancers, 
                                    from developers to designers and creative freelancers, copywriters, 
                                    campaign managers, marketing agencies and marketers, customer support reps, and more. 
                                </p>
                                <ul class="list-1">
                                    <li>
                                        <b>You can get started by posting a job.</b> Give us some background information 
                                        about your project and the specific skills required. <br>Learn how. 
                                    </li>
                                    <li>
                                        <b>Yohli studies your needs.</b> Our search functionality uses data science to highlight the best freelancers based on their skills. Our goal is to find talent that’s a good match. 
                                    </li>
                                    <li>
                                        <b>We suggest to you a short list of possible candidates.</b> You can also search our site for specialized freelancers who can view your job and submit proposals as well.
                                    </li>
                                </ul>
                            </div>                        
                        </div>

                        <div class="row pt-4 align-items-center">
                            <div class="col-md-6 col-sm-12 py-5">
                                <img class="img-center" src="{{ asset("assets/images/how_it_works/2.png") }}">
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <h1 class="section-head">Find Quality Freelancers</h1>
                                <p class="pt-2">Discovered a potential freelancer you would like to work with?</p>
                                <ul class="list-1">
                                    <li>
                                        <b>Browse freelancer profiles</b>, view finalists’ Yohli client ratings, portfolios, 
                                        overall job success scores, and more.                                        . 
                                    </li>
                                    <li>
                                        <b>Review proposals</b>, assess bids, taking into account their qualifications, thought process, timeline, and overall cost. 
                                    </li>
                                    <li>
                                        <b>Start chatting with them.</b> Be sure to ask specific questions and determine who’s the best fit, and contract.
                                    </li>
                                </ul>
                                <p>What do I look for when hiring on Yohli?
                                <br><br>You can evaluate freelancers by reviewing their Yohli profile. 
                                Each freelancer in our marketplace has a Yohli profile. 
                                It can include work experience and previous work, client feedback, skills test scores, 
                                and much more details. You may also want to look for freelancers in our Top Rated and Rising Talent programs. 
                                Then, have a quick interview with your top candidates to answer questions related to your project.
                                </p>
                                        
                            </div>
                        </div>

                        <div class="row pt-4 align-items-center">
                            <div class="col-md-4 col-sm-12 order-lg-3 py-5">
                                <img class="img-center" src="{{ asset("assets/images/how_it_works/3.png") }}">
                            </div>                            
                            <div class="col-md-2 order-lg-2"></div>
                            <div class="col-md-6 col-sm-12 order-lg-1">
                                <h1 class="section-head">Work efficiently, effectively</h1>
                                <p class="pt-2">
                                    Each project includes an online workspace shared by your team and your freelancer, allowing you to:
                                </p>
                                <ul class="list-1">
                                    <li>
                                        Send and receive files. Deliver digital assets in a secure environment.
                                    </li>
                                    <li>
                                        Share feedback in real-time. Use Yohli Messages to communicate via text, chat, or video.
                                    </li>
                                </ul>
                            </div>
                            
                        </div>

                        <div class="row pt-4 align-items-center">
                            <div class="col-md-6 col-sm-12 py-5">
                                <img src="{{ asset("assets/images/how_it_works/4.png") }}">
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <h1 class="section-head">Pay when you are satisfied!</h1>
                                <p class="pt-2">
                                    Pay safely using our Milestone Payment system. Release payments according to a schedule of milestones you set or pay once project is complete. You are in control, so you get to make the decisions.
                                </p>
                            </div>
                        </div>

                    </div>
                  </article>
                
                  <article class="tab__content" id="tab-two">
                    <h1 class="tab__title" data-tab="header">Yohli Freelancers</h1>
                    <div class="tab__body" data-tab="body">
                        <div class="row align-items-center">
                            <div class="col-md-6 col-sm-12 order-lg-2 py-5">
                                <img class="img-center" src="{{ asset("assets/images/how_it_works/5.png") }}">
                            </div>
                            <div class="col-md-6 col-sm-12 order-lg-1">
                                <h1 class="section-head">How do I get started?</h1>
                                <p class="pt-2"></p>
                                <ul class="list-1">
                                    <li>Complete your profile
                                        <ul class="list-2">
                                            <li>Select your skills and expertise.</li>
                                            <li>Upload a professional profile photo.</li>
                                            <li>Go through the Verification Center checklist.</li>
                                        </ul>
                                    </li>
                                    <li>Browse jobs that suit your skills, expertise, price, and schedule
                                        <ul class="list-2">
                                            <li>We have jobs available for all skillset. Maximize your job opportunities by 
                                            optimizing your filters. Save your search and get alerted when relevant jobs are available.</li>
                                        </ul>
                                    </li>
                                    <li>Write your best bid
                                         <ul class="list-2">
                                            <li>Put your best foot forward and write the best pitch possible. 
                                                Read the project and let the clients know you understand their brief. Tell them why you're the best person for this job.
                                                Writing a new brief for each project is more effective than using the same one!
                                            </li>
                                        </ul>
                                    </li>
                                    <li>Get awarded and earn
                                        <ul class="list-2">
                                            <li>Get ready to work once you get hired. 
                                                Deliver high quality work and earn the agreed amount.
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>                        
                        </div>

                        <div class="row pt-4 align-items-center">
                            <div class="col-md-6 col-sm-12 py-5">
                                <img class="img-center" src="{{ asset("assets/images/how_it_works/6.png") }}">
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <h1 class="section-head">Service fees</h1>
                                <p class="pt-2">
                                    Yohli charges freelancers a 10% fee for the total project cost.
                                    <br>
                                    <a href="#">See the legal page for all more details.</a>                                        
                                </p>
                            </div>
                        </div>

                        <div class="row pt-4 align-items-center">
                            <div class="col-md-4 col-sm-12 order-lg-3 py-5">
                                <img class="img-center" src="{{ asset("assets/images/how_it_works/7.png") }}">
                            </div>                            
                            <div class="col-md-2 order-lg-2"></div>
                            <div class="col-md-6 col-sm-12 order-lg-1">
                                <h1 class="section-head">Manage Your Career</h1>
                                <p class="pt-2"></p>
                                <ul class="list-1">
                                    <li>
                                        Stay up to date on the yohli.com marketplace and keep in touch with your clients.
                                    </li>
                                    <li>
                                        Collaborate with your clients on the go and get alerted on the latest jobs 
                                        with our mobile app and desktop platform.
                                    </li>
                                    <li>
                                        Our job alerts system will keep you updated once new projects that 
                                        fit your skills and expertise become available. 
                                        Bid away!
                                    </li>
                                </ul>
                            </div>
                            
                        </div>

                        <div class="row pt-4 align-items-center">
                            <div class="col-md-6 col-sm-12 py-5">
                                <img src="{{ asset("assets/images/how_it_works/8.png") }}">
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <h1 class="section-head">Safe and Secure</h1>
                                <p class="pt-2">yohli.com is a community that values 
                                    your trust and safety as our number one priority:
                                </p>
                                <ul class="list-1">
                                    <li>
                                        State-of-the-art security for your funds. All transactions are secured with DigiCert 4096-bit SSL encryption.
                                    </li>
                                    <li>
                                        Request for Milestone Payments from your clients to make sure that your hard-earned money gets to you safely.
                                    </li>
                                    <li>
                                        Our representatives are available 24/7 to assist you with any issues.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                  </article>
                </section>
            </div>
        </div>

    </div>

</div>

@endsection