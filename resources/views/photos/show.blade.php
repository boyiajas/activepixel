@extends('layouts.guest')

@section('content')
<style>
    .zoomable {
    position: relative;
    overflow: hidden;
    border-radius: 5px;
    /* box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2); */
    text-align: center;
    border: solid 1px #fff;/* #5ce1e6; */
    background-color: #fff;
}

.zoomable__img {
    transform-origin: var(--zoom-pos-x, 0%) var(--zoom-pos-y, 0%);
    transition: transform 0.15s linear;
    height: 700px;
}

.zoomable--zoomed .zoomable__img {
    cursor: zoom-in;
    transform: scale(var(--zoom, 2));
}

.zoom-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(0, 151, 178, 0.9);
        border-radius: 50%;
        padding: 10px;
        color: #fff;
        font-size: 20px;
        z-index: 10;
        padding-top: 5px;
        padding-bottom: 5px;
    }
</style>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-7">
            <!-- Lead Image Display -->
            <div class="lead-image mb-4 zoomable">
                @php
                    // Find the position of the last dot (.) before the extension
                    $lastDotPosition = strrpos($lead_image, '.');

                    // Extract the base name and the extension
                    $baseName = substr($lead_image, 0, $lastDotPosition);
                    $extension = substr($lead_image, $lastDotPosition);

                    // Create the watermarked image path
                    $watermarked_image = $baseName . '.watermark' . $extension;
                @endphp
                <img src="{{ asset($watermarked_image) }}" alt="{{ $photo->name }}" class="img-fluid zoomable__img">
                <div class="zoom-icon">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>

            <!-- Regular Images Display -->
            <div class="regular-images mb-4">
                <h5>More Views</h5>
                <div class="row">
                    @foreach($regular_images as $image)
                        <div class="col-md-3 mb-3">
                            <img src="{{ asset($image->file_path) }}" alt="{{ $photo->name }}" class="img-fluid">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recommended Photos Section -->
            <div class="recommended-photos mt-5">
                <h5>Recommended Photos</h5>
                <div class="row">
                    @foreach($recommendedPhotos as $recommended)
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('individual.photos', $recommended->id) }}">
                                <img src="{{ asset('/'.$recommended->leadImage()?->file_path) }}" alt="{{ $recommended->name }}" class="img-fluid">
                                <p class="mt-2">{{ $recommended->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white">
                    <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('events.all') }}">Events</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('events.photos', $photo->event->id) }}">{{$photo->event->name}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Race No {{$photo->name}}</li>
                </ol>
            </nav>
            <!-- Event Details and Add to Cart Section -->
            <div class="event-details">
                <h3>{{ $photo->event->name }}</h3>
                <p><strong>Slug:</strong> {{ $photo->event->slug }}</p>
                <p>{{ $photo->event->description }}</p>

                <!-- Add to Cart Button -->
                <div class="add-to-cart mt-4">
                    <form action="{{ route('cart.add', ['photo_id' => $photo->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="guest_token" value="{{ session('guest_token') }}">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-shopping-cart"></i> Add to Cart - R{{ number_format($photo->price, 2) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /*
 * Constants
 */
const Default = {
    initialZoom: 3,
    minZoom: 1.25,
    maxZoom: 4,
    zoomSpeed: 0.01
};

/*
 * Class definition
 */
class Zoomable {
    constructor(element, config) {
        this.element = element;
        this.config = this._mergeConfig(config);

        const { initialZoom, minZoom, maxZoom } = this.config;

        this.zoomed = false;
        this.initialZoom = Math.max(Math.min(initialZoom, maxZoom), minZoom);
        this.zoom = this.initialZoom;

        this.img = element.querySelector(".zoomable__img");
        this.img.draggable = false;
        this.element.style.setProperty("--zoom", this.initialZoom);

        this._addEventListeners();
    }

    static get Default() {
        return Default;
    }

    _addEventListeners() {
        this.element.addEventListener("mouseover", () =>
            this._handleMouseover()
        );
        this.element.addEventListener("mousemove", (evt) =>
            this._handleMousemove(evt)
        );
        this.element.addEventListener("mouseout", () => this._handleMouseout());
        this.element.addEventListener("wheel", (evt) => this._handleWheel(evt));

        this.element.addEventListener("touchstart", (evt) =>
            this._handleTouchstart(evt)
        );
        this.element.addEventListener("touchmove", (evt) =>
            this._handleTouchmove(evt)
        );
        this.element.addEventListener("touchend", () => this._handleTouchend());
    }

    _handleMouseover() {
        if (this.zoomed) {
            return;
        }

        this.element.classList.add("zoomable--zoomed");

        this.zoomed = true;
    }

    _handleMousemove(evt) {
        if (!this.zoomed) {
            return;
        }

        const elPos = this.element.getBoundingClientRect();

        const percentageX = `${
            ((evt.clientX - elPos.left) * 100) / elPos.width
        }%`;
        const percentageY = `${
            ((evt.clientY - elPos.top) * 100) / elPos.height
        }%`;

        this.element.style.setProperty("--zoom-pos-x", percentageX);
        this.element.style.setProperty("--zoom-pos-y", percentageY);
    }

    _handleMouseout() {
        if (!this.zoomed) {
            return;
        }

        this.element.style.setProperty("--zoom", this.initialZoom);
        this.element.classList.remove("zoomable--zoomed");

        this.zoomed = false;
    }

    _handleWheel(evt) {
        if (!this.zoomed) {
            return;
        }

        evt.preventDefault();

        const newZoom = this.zoom + evt.deltaY * (this.config.zoomSpeed * -1);
        const { minZoom, maxZoom } = this.config;

        this.zoom = Math.max(Math.min(newZoom, maxZoom), minZoom);
        this.element.style.setProperty("--zoom", this.zoom);
    }

    _handleTouchstart(evt) {
        evt.preventDefault();

        this._handleMouseover();
    }

    _handleTouchmove(evt) {
        if (!this.zoomed) {
            return;
        }

        const elPos = this.element.getBoundingClientRect();

        let percentageX =
            ((evt.touches[0].clientX - elPos.left) * 100) / elPos.width;
        let percentageY =
            ((evt.touches[0].clientY - elPos.top) * 100) / elPos.height;

        percentageX = Math.max(Math.min(percentageX, 100), 0);
        percentageY = Math.max(Math.min(percentageY, 100), 0);

        this.element.style.setProperty("--zoom-pos-x", `${percentageX}%`);
        this.element.style.setProperty("--zoom-pos-y", `${percentageY}%`);
    }

    _handleTouchend(evt) {
        this._handleMouseout();
    }

    _mergeConfig(config) {
        return {
            ...this.constructor.Default,
            ...(typeof config === "object" ? config : {})
        };
    }
}

/*
 * Implementation
 */
const zoomables = document.querySelectorAll(".zoomable");

for (const el of zoomables) {
    new Zoomable(el);
}

</script>
@endsection
