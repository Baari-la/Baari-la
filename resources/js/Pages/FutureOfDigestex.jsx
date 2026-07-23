import { Head, usePage } from "@inertiajs/react";
import UpcomingIntelligence from "@/Components/Home/UpcomingIntelligence";

export default function FutureOfDigestex() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    return (
        <>
            <Head
                title={
                    isEn
                        ? "DIGESTEX Strategic Roadmap 2026-2030"
                        : "Roadmap Strategis DIGESTEX 2026-2030"
                }
            />

            <UpcomingIntelligence />
        </>
    );
}
