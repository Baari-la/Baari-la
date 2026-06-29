import HeroBackground from "./HeroBackground";
import HeroContent from "./HeroContent";
import HeroButtons from "./HeroButtons";
import LiveIndustryStatus from "./LiveIndustryStatus";

export default function HeroSection() {
    return (
        <section className="relative overflow-hidden bg-slate-950 text-white">
            <HeroBackground>
                <div className="container mx-auto px-6">
                    <div className="grid min-h-[700px] items-center gap-12 py-24 lg:grid-cols-2">
                        {/* LEFT */}

                        <div className="relative z-10">
                            <HeroContent />

                            <HeroButtons />
                        </div>

                        {/* RIGHT */}

                        <div className="relative hidden lg:flex items-center justify-center">
                            <div className="relative">
                                <div className="absolute inset-0 rounded-full bg-blue-600/20 blur-3xl" />

                                <img
                                    src="/images/home/digital-globe.png"
                                    alt="Global Textile Industry"
                                    className="relative w-[480px] animate-pulse"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </HeroBackground>

            <LiveIndustryStatus />
        </section>
    );
}
