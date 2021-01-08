@extends('layouts.layout')

@section('title','Faq')
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card faq-box">
                <div class="card-body">
                    <div class="faq-icon">
                        <i class="dripicons-question h2 icon-one"></i>
                        <i class="dripicons-question h2 icon-two"></i>
                    </div>
                    <h5 class="font-16 mb-3">What is Lorem Ipsum?</h5>
                    <p class="text-muted mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                </div>
            </div>
            <!-- end faq-box -->

            <div class="card faq-box">
                <div class="card-body">
                    <div class="faq-icon">
                        <i class="dripicons-question h2 icon-one"></i>
                        <i class="dripicons-question h2 icon-two"></i>
                    </div>
                    <h5 class="font-16 mb-3">Where does it come from?
                        @can('ban-users')
                            <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fab fa-wpexplorer"></i> View</button>
                        @endcan
                    </h5>
                    <p class="text-muted mb-0">If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental.</p>
                </div>
            </div>
            <!-- end faq-box -->

            <div class="card faq-box">
                <div class="card-body">
                    <div class="faq-icon">
                        <i class="dripicons-question h2 icon-one"></i>
                        <i class="dripicons-question h2 icon-two"></i>
                    </div>
                    <h5 class="font-16 mb-3">Why do we use it?</h5>
                    <p class="text-muted mb-0">It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is. The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary.</p>
                </div>
            </div>
            <!-- end faq-box -->

        </div>
        <!-- end col -->

        <div class="col-lg-6">
            <div class="card faq-box">
                <div class="card-body">
                    <div class="faq-icon">
                        <i class="dripicons-question h2 icon-one"></i>
                        <i class="dripicons-question h2 icon-two"></i>
                    </div>
                    <h5 class="font-16 mb-3">Where can I get some?</h5>
                    <p class="text-muted mb-0">At solmen va esser necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues coalesce, li grammatica del resultant lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va esser plu simplic e.</p>
                </div>
            </div>
            <!-- end faq-box -->

            <div class="card faq-box">
                <div class="card-body">
                    <div class="faq-icon">
                        <i class="dripicons-question h2 icon-one"></i>
                        <i class="dripicons-question h2 icon-two"></i>
                    </div>
                    <h5 class="font-16 mb-3">Why do we use it?</h5>
                    <p class="text-muted mb-0">A un angleso it va semblar un simplificat Angles quam un skeptic Cambridge amico dit me que Occidental es. li Europan lingues es membres del sam familie Lor separat existentie es un myth. por scientie, music, sport etc, litot Europa usa li sam vocabular.</p>
                </div>
            </div>
            <!-- end faq-box -->

            <div class="card faq-box">
                <div class="card-body">
                    <div class="faq-icon">
                        <i class="dripicons-question h2 icon-one"></i>
                        <i class="dripicons-question h2 icon-two"></i>
                    </div>
                    <h5 class="font-16 mb-3">Where does it come from?</h5>
                    <p class="text-muted mb-0">Their separate existence is a myth. For science music sport etc Europe uses the same vocabulary. The languages only differ in their grammar, their pronunciation and their most common words. Everyone realizes why a new common language would be desirable one could refuse to pay expensive translators. to achieve this it would be necessary</p>
                </div>
            </div>
            <!-- end faq-box -->

        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@stop
