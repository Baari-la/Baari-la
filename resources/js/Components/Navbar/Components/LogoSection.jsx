import { Link } from "@inertiajs/react";

export default function LogoSection() {
    return (
        <Link
            href={route("home")}
            className="flex flex-col items-start shrink-0"
        >
            <img
                src="/images/logoWeb.png"
                className="h-12 w-auto"
                alt="Digestex Global"
            />

            <p
                className="
                    mt-1
                    text-[9px]
                    leading-none
                    tracking-[0.15em]
                "
            >
                <span className="text-[#0B2E59]">Where Textile Meets</span>{" "}
                <span className="font-semibold text-[#F97316]">
                    Intelligence
                </span>
            </p>
        </Link>
    );
}
