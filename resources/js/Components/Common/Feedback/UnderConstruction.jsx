import { Hammer } from "lucide-react";

export default function UnderConstruction({
    title = "Under Development",

    description = "This feature is currently under development and will be available soon.",
}) {
    return (
        <div className="rounded-3xl bg-gradient-to-br from-blue-600 to-indigo-700 p-10 text-center text-white">
            <Hammer size={50} className="mx-auto" />

            <h2 className="mt-6 text-2xl font-bold">{title}</h2>

            <p className="mt-4 text-blue-100">{description}</p>
        </div>
    );
}
